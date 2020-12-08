<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\Form\FilmType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    //////////////////////////////////////////FILM//////////////////////////////////////////////


    /**
     * @Route("/user/ajouterFilms", name="ajouterFilms")
     * @Route("/user/modifierFilms/{id}", name="modifierFilms")
     */
    public function formulaireFilms(Film $film = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger, UserInterface $user)
    {
        //$Films = new Films(); //est null de base à la difference de l'élement cidessus

        if (!$film) { //{id} va recuperer toutes les donnée de la base
            $film = new Film();
        }

        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$film) {
                $film->setDateDeSortieAt(new \DateTime());
            }
            $film->setUser($user);

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFilm')->getData();

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
                        $this->getParameter('dossier_imageFilms'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'image' property to store the PDF file name
                // instead of its contents
                $film->setImageFilm($newFilename);
            }

            // ... persist the $user variable or any other work

            $film->setAjouter(true);

            $objectManager->persist($film);
            $objectManager->flush();
            return $this->redirectToRoute("user");
        }

        $mode = false;
        if ($film->getId() !== null) {
            $mode = true;
        }
        return $this->render('user/createUserF.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }

    /**
     * @Route("/user/montrerFilms", name="montrerFilms")
     */
    public function afficheFilms(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $films = $repository->findFilmByUser($user->getId());
        
        return $this->render('user/montrerFilms.html.twig', [
            'films' => $films
        ]);
    }


    /**
     * @Route("/user/montrerLeFilm/{id}", name="montrerLeFilm")
     */
    public function afficheLeFilms($id)
    {
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $film = $repository->find($id);


        return $this->render('user/montrerLeFilm.html.twig', [
            'film' => $film
        ]);
    }

    /**
     * @Route("/user/supprimeFilm/{id}", name="supprimeFilms")
     */
    public function supprimeFilm(Film $film)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($film);
        $objectManager->flush();
        return $this->redirectToRoute("montrerFilms");
    }
}
