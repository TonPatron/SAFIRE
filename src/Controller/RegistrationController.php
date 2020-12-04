<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function creationFormulaire(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger)
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setInscriptionAt(new \DateTime());

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('dossier_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'image' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }

            // ... persist the $user variable or any other work

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('safire');
        }
        return $this->render('registration/index.html.twig', [
            "formulaire" => $form->createView()

        ]);
    }
}
