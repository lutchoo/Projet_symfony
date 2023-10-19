<?php

namespace App\Controller;

use App\Entity\Boardgame;
use App\Repository\CategoriesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OwnerController extends AbstractController
{
    #[Route('/owner', name: 'app_owner')]
    public function index(): Response
    {
        return $this->render('owner/index.html.twig', [
            'controller_name' => 'OwnerController',
        ]);
    }
    #[Route('/owner/{id}', name: 'app_owner_boardgames', methods: ['GET','POST'])]
    public function getBoardgamesByOwner(CategoriesRepository $categorie ,UserRepository $userR, $id) :Response
    {
        $boardgames = $userR->find($id)->getBoardgames();
        return $this->render('owner/index.html.twig',[
            'boardgames'=>$boardgames,
            'categories' => $categorie->findAll(),
        ]);
    }

    #[Route('/owner/show/{id}', name:'app_owner_show', methods: ['GET','POST'])]
    public function show(CategoriesRepository $categorie , Boardgame $boardgame, $id ):Response
    {
        $category = $boardgame->getCategorie(); 
        return $this->render('game/show.html.twig', [
         'boardgame' => $boardgame,
         'categories' => $categorie->findAll(),
         'category' => $category,
        ]);
    }

}
