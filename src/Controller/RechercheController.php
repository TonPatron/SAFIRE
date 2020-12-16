<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\AnimeRepository;
use App\Repository\SerieRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", methods="GET", name="recherche")
     * 
     */
    public function indexA(AnimeRepository $animeRepository , Request $request): Response

    {

        $searchAnime = $animeRepository->findAllMatching($request->get("recherche"));
        return $this->json($searchAnime);
    }






    ////////////////////////////////////////////////////RESHEARCH///////////////////////////////////////////////////////////////////////



}
