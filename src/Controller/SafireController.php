<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Anime;
use App\Entity\Serie;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SafireController extends AbstractController
{
    /**
     * @Route("/", name="safire")
     */
    public function index(): Response
    {
        return $this->render('safire/index.html.twig', [
            'controller_name' => 'SafireController',
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription()
    {
        return $this->render('safire/index.html.twig');
    }

    /**
     * @Route("/mentionLegale", name="mentionLegale")
     */
    public function mentionLegale()
    {
        return $this->render('security/mentionLegale.html.twig');
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu()
    {
        return $this->render('security/cgu.html.twig');
    }




    

    ////////////////////////////////////////AFFICHAGE///////////////////////////////////////////////////


    ////////////////Film////////////////////


    /**
     * @Route("/safire/afficheAllFilms", name="afficheAllFilms")
     */
    public function afficheAllFilms()
    {
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $films = $repository->findAll();

        return $this->render('safire/afficheAllFilms.html.twig', [
            'films' => $films
        ]);
    }


    /**
     * @Route("/safire/afficheLeFilm/{id}", name="afficheLeFilm")
     */
    public function afficheduFilm($id)
    {
        $repository = $this->getDoctrine()->getRepository(Film::class);
        $film = $repository->find($id);

        return $this->render('safire/afficheLeFilm.html.twig', [
            'film' => $film
        ]);
    }

    ////////////////Serie////////////////////

    /**
     * @Route("/safire/afficheAllSeries", name="afficheAllSeries")
     */
    public function afficheAllSeries()
    {
        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $series = $repository->findAll();

        return $this->render('safire/afficheAllSeries.html.twig', [
            'series' => $series
        ]);
    }


    /**
     * @Route("/safire/afficheLaSerie/{id}", name="afficheLaSerie")
     */
    public function afficheDuneSerie($id)
    {
        $repository = $this->getDoctrine()->getRepository(Serie::class);
        $serie = $repository->find($id);

        return $this->render('safire/afficheLaSerie.html.twig', [
            'serie' => $serie
        ]);
    }

    ////////////////Anime////////////////////

    /**
     * @Route("/safire/afficheAllAnimes", name="afficheAllAnimes")
     */
    public function afficheAllAnimes()
    {
        $repository = $this->getDoctrine()->getRepository(Anime::class);
        $animes = $repository->findAll();

        return $this->render('safire/afficheAllAnimes.html.twig', [
            'animes' => $animes
        ]);
    }


    /**
     * @Route("/safire/afficheLanime/{id}", name="afficheLanime")
     */
    public function afficheDunAnime($id)
    {
        $repository = $this->getDoctrine()->getRepository(Anime::class);
        $anime = $repository->find($id);

        return $this->render('safire/afficheLanime.html.twig', [
            'anime' => $anime
        ]);
    }


    ////////////////////////////////////////RECHERCHE///////////////////////////////////////////////////








    // /**
    //  * @Route("/safire/categorieFilm/", name="categorieFilm")
    //  */
    // public function afficheFilmsByCategorie()
    // {
    //     $repository = $this->getDoctrine()->getRepository(Categorie::class);
    //     $films = $repository->findFilmByCategorie();

    //     return $this->render('safire/categorieFilm.html.twig', [
    //         'films' => $films
    //     ])

    // }


}
