<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Films;
use App\Form\AdminType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/inscriptionMembre", name="inscriptionMembre")
     */
    public function creationFormulaire(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger)
    {
        $user = new User;
        $form = $this->createForm(AdminType::class, $user);
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
        return $this->render('admin/inscription.html.twig', [
            "formulaire" => $form->createView()

        ]);
    }



    /**
     * @Route("/admin/addFilms", name="addFilms")
     * @Route("/admin/editFilms/{id}", name="editFilms")
     */
    public function formulaireFilms(Films $Films = null, Request $request, ObjectManager $objectManager)
    {
        //$Films = new Films(); //est null de base à la difference de l'élement cidessus

        if (!$Films) { //{id} va recuperer toutes les donnée de la base
            $Films = new Films();
        }

        $form = $this->createForm(FilmsType::class, $Films);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$Films) {
                $Films->setEmbaucheAt(new \DateTime());
            }
            $objectManager->persist($Films);
            $objectManager->flush();
            return $this->redirectToRoute("showFilms");
        }

        $mode = false;
        if ($Films->getId() !== null) {
            $mode = true;
        }
        return $this->render('admin/createF.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }

    /**
     * @Route("/entreprise/showFilms", name="showFilms")
     */
    public function afficheFilms()
    {
        $repository = $this->getDoctrine()->getRepository(Films::class);
        $employes = $repository->findAll();

        return $this->render('admin/showFilms.html.twig', [
            'employes' => $employes
        ]);
    }
}
