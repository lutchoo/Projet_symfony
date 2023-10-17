<?php

namespace App\Controller;

use App\Entity\Boardgame;
use App\Form\BoardgameType;
use App\Repository\BoardgameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(BoardgameRepository $boardgameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'boardgames' => $boardgameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $boardgame = new Boardgame();
        $form = $this->createForm(BoardgameType::class, $boardgame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardgame = $form->getData();
            $boardgame->setOwner($this->getUser());
            $entityManager->persist($boardgame);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/new.html.twig', [
            'boardgame' => $boardgame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Boardgame $boardgame): Response
    {
        return $this->render('game/show.html.twig', [
            'boardgame' => $boardgame,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boardgame $boardgame, EntityManagerInterface $entityManager): Response
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
