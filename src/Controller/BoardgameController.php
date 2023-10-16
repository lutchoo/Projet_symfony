<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardgameController extends AbstractController
{
    #[Route('/boardgame', name: 'app_boardgame')]
    public function index(): Response
    {
        return $this->render('boardgame/index.html.twig', [
            'controller_name' => 'BoardgameController',
        ]);
    }
}
