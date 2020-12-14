<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\Entity\Anime;
use App\Entity\Serie;
use App\Form\FilmType;
use App\Form\AnimeType;
use App\Form\SerieType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Forms;
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

    /**
     * @Route("/user/monProfil/", name="monProfil")
     */
    public function afficheMonProfil(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $profil = $repository->find($user->getId());


        return $this->render('user/monProfil.html.twig', [
            'user' => $profil
        ]);
    }









    //////////////////////////////////////////FILM//////////////////////////////////////////////


    /**
     * @Route("/user/ajouterFilms", name="ajouterFilms")
     * @Route("/user/modifierFilms/{id}", name="modifierFilms")
     */
    public function formuFilms(Film $film = null,  Request $request, ObjectManager $objectManager, SluggerInterface $slugger, UserInterface $user)
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
            return $this->redirectToRoute("ajouterFilms");
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


    //////////////////////////////////////////SERIE//////////////////////////////////////////////


    /**
     * @Route("/user/ajouterSeries", name="ajouterSeries")
     * @Route("/user/modifierSeries/{id}", name="modifierSeries")
     */
    public function formuSeries(Serie $serie = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger, UserInterface $user)
    {
        //$Series = new Series(); //est null de base à la difference de l'élement cidessus

        if (!$serie) { //{id} va recuperer toutes les donnée de la base
            $serie = new Serie();
        }

        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$serie) {
                $serie->setDateDeSortieAt(new \DateTime());
            }
            $serie->setUser($user);

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageSerie')->getData();

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
                        $this->getParameter('dossier_imageSeries'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'image' property to store the PDF file name
                // instead of its contents
                $serie->setImageSerie($newFilename);
            }

            // ... persist the $user variable or any other work

            $serie->setAjouter(true);

            $objectManager->persist($serie);
            $objectManager->flush();
            return $this->redirectToRoute("ajouterSeries");
        }

        $mode = false;
        if ($serie->getId() !== null) {
            $mode = true;
        }
        return $this->render('user/createUserS.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }

    /**
     * @Route("/user/montrerSeries", name="montrerSeries")
     */
    public function afficheSeries(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $series = $repository->findSerieByUser($user->getId());

        return $this->render('user/montrerSeries.html.twig', [
            'series' => $series
        ]);
    }


    /**
     * @Route("/user/montrerLeSerie/{id}", name="montrerLaSerie")
     */
    public function afficheLeSeries($id)
    {
        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $serie = $repository->find($id);


        return $this->render('user/montrerLaSerie.html.twig', [
            'serie' => $serie
        ]);
    }

    /**
     * @Route("/user/supprimeSerie/{id}", name="supprimeSeries")
     */
    public function supprimeSerie(Serie $serie)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($serie);
        $objectManager->flush();
        return $this->redirectToRoute("montrerSeries");
    }



    //////////////////////////////////////////ANIME//////////////////////////////////////////////


    /**
     * @Route("/user/ajouterAnimes", name="ajouterAnimes")
     * @Route("/user/modifierAnimes/{id}", name="modifierAnimes")
     */
    public function formuAnimes(Anime $anime = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger, UserInterface $user)
    {
        //$Animes = new Animes(); //est null de base à la difference de l'élement cidessus

        if (!$anime) { //{id} va recuperer toutes les donnée de la base
            $anime = new Anime();
        }

        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$anime) {
                $anime->setDateDeSortieAt(new \DateTime());
            }
            $anime->setUser($user);

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageAnime')->getData();

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
                        $this->getParameter('dossier_imageAnimes'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'image' property to store the PDF file name
                // instead of its contents
                $anime->setImageAnime($newFilename);
            }

            // ... persist the $user variable or any other work

            $anime->setAjouter(true);

            $objectManager->persist($anime);
            $objectManager->flush();
            return $this->redirectToRoute("ajouterAnimes");
        }

        $mode = false;
        if ($anime->getId() !== null) {
            $mode = true;
        }
        return $this->render('user/createUserA.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }

    /**
     * @Route("/user/montrerAnimes", name="montrerAnimes")
     */
    public function afficheAnimes(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $animes = $repository->findAnimeByUser($user->getId());

        return $this->render('user/montrerAnimes.html.twig', [
            'animes' => $animes
        ]);
    }


    /**
     * @Route("/user/montrerLanime/{id}", name="montrerLanime")
     */
    public function afficheLeAnimes($id)
    {
        $repository = $this->getDoctrine()->getRepository(Anime::class);
        $anime = $repository->find($id);


        return $this->render('user/montrerLanime.html.twig', [
            'anime' => $anime
        ]);
    }

    /**
     * @Route("/user/supprimeAnime/{id}", name="supprimeAnimes")
     */
    public function supprimeAnime(Anime $anime)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($anime);
        $objectManager->flush();
        return $this->redirectToRoute("montrerAnimes");
    }
}
