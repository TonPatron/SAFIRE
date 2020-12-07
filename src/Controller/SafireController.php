<?php

namespace App\Controller;

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
