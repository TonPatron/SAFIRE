<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    
}
