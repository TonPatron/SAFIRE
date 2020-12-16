<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
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
    public function index(AnimeRepository $animeRepository, ObjectManager $objectManager, Request $request): Response

    {

        $searchAnime = $animeRepository->findAllMatching($request->get("recherche"));

        return $this->json($searchAnime);

    }

    ////////////////////////////////////////////////////RESHEARCH///////////////////////////////////////////////////////////////////////



    /**
     * @Route("/admin/user", methods="GET", name="admin_utility_users")
    
     */




    /*  public function getId(AnimeRepository $animeRepository, Request $request)
    {
        $anime = $animeRepository->findAllMatching($request->query->get('query'));
    }
 */
}
