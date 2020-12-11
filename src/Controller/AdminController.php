<?php

namespace App\Controller;


use App\Entity\Film;
use App\Entity\User;

use App\Entity\Anime;
use App\Entity\Serie;
use App\Form\AdminType;
use App\Entity\Categorie;
use App\Form\FilmAdminType;
use App\Form\AnimeAdminType;
use App\Form\SerieAdminType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin55555", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    ////////////////////////////////////////////////////INSCRIPTION///////////////////////////////////////////////////////////////////////



    /**
     * @Route("/user/profil/", name="profil")
     */
    public function showProfil( UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $profil= $repository->find($user->getId());


        return $this->render('admin/profil.html.twig', [
            'user' => $profil
        ]);
    }



    /**
     * @Route("/admin/inscriptionMembre", name="inscriptionMembre")
     * @Route("/admin/editerMembre/{id}", name="editerMembre")
     */
    public function creationFormulaire(User $user = null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger)
    {
        if (!$user) {
            $user = new User();
        }

        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            if (!$user) {
                $user->setInscriptionAt(new \DateTime());
            }

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

        $mode = false;
        if ($user->getId() !== null) {
            $mode = true;
        }

        return $this->render('admin/inscription.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }


    /**
     * @Route("/admin/showMembres", name="showMembres")
     */
    public function afficheMembres()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('admin/showMembres.html.twig', [
            'users' => $users
        ]);
    }


    /**
     * @Route("/admin/showLeMembre/{id}", name="showLeMembre")
     */
    public function afficheLeMembres($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        return $this->render('admin/showLeMembre.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/removeMembres/{id}", name="removeMembres")
     */
    public function deleteMembre(User $user)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($user);
        $objectManager->flush();
        return $this->redirectToRoute("showMenmbres");
    }



    ////////////////////////////////////////////////////FILM///////////////////////////////////////////////////////////////////////

    /**
     * @Route("/admin/addFilms", name="addFilms")
     * @Route("/admin/editFilms/{id}", name="editFilms")
     */
    public function formulaireFilms(Film $film = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger)
    {
        //$Films = new Films(); //est null de base à la difference de l'élement cidessus

        if (!$film) { //{id} va recuperer toutes les donnée de la base
            $film = new Film();
        }

        $form = $this->createForm(FilmAdminType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$film) {
                $film->setDateDeSortieAt(new \DateTime());
            }


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
            return $this->redirectToRoute("showFilms");
        }

        $mode = false;
        if ($film->getId() !== null) {
            $mode = true;
        }

        return $this->render('admin/createF.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }

    /**
     * @Route("/admin/showFilms", name="showFilms")
     */
    public function afficheFilms()
    {
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $films = $repository->findAll();

        return $this->render('admin/showFilms.html.twig', [
            'films' => $films
        ]);
    }


    /**
     * @Route("/admin/showLeFilm/{id}", name="showLeFilm")
     */
    public function afficheLeFilms($id)
    {
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $film = $repository->find($id);

        return $this->render('admin/showLeFilm.html.twig', [
            'film' => $film
        ]);
    }

    /**
     * @Route("/admin/removeFilm/{id}", name="removeFilms")
     */
    public function deleteFilm(Film $film)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($film);
        $objectManager->flush();
        return $this->redirectToRoute("showFilms");
    }




    /////////////////////////////////////////////////////////SERIES//////////////////////////////////////////////////////////////////


    /**
     * @Route("/admin/addSeries", name="addSeries")
     * @Route("/admin/editSeries/{id}", name="editSeries")
     */
    public function formulaireSeries(Serie $serie = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger)
    {
        //$Series = new Series(); //est null de base à la difference de l'élement cidessus

        if (!$serie) { //{id} va recuperer toutes les donnée de la base
            $serie = new Serie();
        }

        $form = $this->createForm(SerieAdminType::class, $serie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$serie) {
                $serie->setDateDeSortieAt(new \DateTime());
            }


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
            return $this->redirectToRoute("showSeries");
        }

        $mode = false;
        if ($serie->getId() !== null) {
            $mode = true;
        }
        return $this->render('admin/createS.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }


    /**
     * @Route("/admin/showSeries", name="showSeries")
     */
    public function afficheSeries()
    {
        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $series = $repository->findAll();

        return $this->render('admin/showSeries.html.twig', [
            'series' => $series
        ]);
    }


    /**
     * @Route("/admin/showLaSerie/{id}", name="showLaSerie")
     */
    public function afficheLeSeries($id)
    {
        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $serie = $repository->find($id);

        return $this->render('admin/showLaSerie.html.twig', [
            'serie' => $serie
        ]);
    }

    /**
     * @Route("/admin/removeSerie/{id}", name="removeSeries")
     */
    public function deleteSerie(Serie $serie)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($serie);
        $objectManager->flush();
        return $this->redirectToRoute("showSeries");
    }






    //////////////////////////////////////////////////////////ANIMES/////////////////////////////////////////////////////////////////



    /**
     * @Route("/admin/addAnimes", name="addAnimes")
     * @Route("/admin/editAnimes/{id}", name="editAnimes")
     */
    public function formulaireAnimes(Anime $anime = null, Request $request, ObjectManager $objectManager, SluggerInterface $slugger)
    {
        //$Animes = new Animes(); //est null de base à la difference de l'élement cidessus

        if (!$anime) { //{id} va recuperer toutes les donnée de la base
            $anime = new Anime();
        }

        $form = $this->createForm(AnimeAdminType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$anime) {
                $anime->setDateDeSortieAt(new \DateTime());
            }


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
            return $this->redirectToRoute("showAnimes");
        }

        $mode = false;
        if ($anime->getId() !== null) {
            $mode = true;
        }
        return $this->render('admin/createA.html.twig', [
            "formulaire" => $form->createView(),
            "mode" => $mode

        ]);
    }



    /**
     * @Route("/admin/showAnimes", name="showAnimes")
     */
    public function afficheAnimes()
    {
        $repository = $this->getDoctrine()->getRepository(Anime::class);
        $animes = $repository->findAll();

        return $this->render('admin/showAnimes.html.twig', [
            'animes' => $animes
        ]);
    }


    /**
     * @Route("/admin/showLanime/{id}", name="showLanime")
     */
    public function afficheLeAnimes($id)
    {
        $repository = $this->getDoctrine()->getRepository(Anime::class);
        $anime = $repository->find($id);

        return $this->render('admin/showLanime.html.twig', [
            'anime' => $anime
        ]);
    }

    /**
     * @Route("/admin/removeAnime/{id}", name="removeAnimes")
     */
    public function deleteAnime(Anime $anime)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $objectManager->remove($anime);
        $objectManager->flush();
        return $this->redirectToRoute("showAnimes");
    }
}
