<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Entity\Boardgame;
use App\Form\BoardgameType;
use App\Repository\BoardgameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(CategoriesRepository $categorie, BoardgameRepository $boardgameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'boardgames' => $boardgameRepository->findAll(),
            'categories' => $categorie->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(CategoriesRepository $categorie, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $boardgame = new Boardgame();
        $form = $this->createForm(BoardgameType::class, $boardgame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardgame = $form->getData();
            $boardgame->setOwner($this->getUser());
            $gamefile = $form->get("img")->getData();
            if ($gamefile){

                $originalFilname = pathinfo($gamefile->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilname);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$gamefile->guessExtension();

                try {
                    $gamefile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }
                $boardgame->setImg($newFilename);
                $entityManager->persist($boardgame);
                $entityManager->flush();
            }
        
            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/new.html.twig', [
            'boardgame' => $boardgame,
            'form' => $form,
            'categories' => $categorie->findAll(),
        ]);
    }
    
    
    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(CategoriesRepository $categorie ,Boardgame $boardgame): Response
    {

        $category = $boardgame->getCategorie();
        return $this->render('game/show.html.twig', [
         'boardgame' => $boardgame,
         'categories' => $categorie->findAll(),
         'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(CategoriesRepository $categorie ,Request $request, Boardgame $boardgame, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BoardgameType::class, $boardgame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/edit.html.twig', [
            'boardgame' => $boardgame,
            'form' => $form,
            'categories' => $categorie->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Boardgame $boardgame, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boardgame->getId(), $request->request->get('_token'))) {
            $entityManager->remove($boardgame);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}
