<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Entity\Boardgame;
use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservations')]
class ReservationsController extends AbstractController
{
    #[Route('/', name: 'app_reservations_index', methods: ['GET'])]
    public function index(CategoriesRepository $categories, ReservationsRepository $reservationsRepository): Response
    {
        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservationsRepository->findAll(),
            'categories' => $categories->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservations_new', methods: ['GET', 'POST'])]
    public function new(CategoriesRepository $categories, Request $request, EntityManagerInterface $entityManager, $id, Boardgame $boardgame): Response
    {
        
        $game = $entityManager->getRepository(Boardgame::class)->find($id);
        $reservation_exist = $game->getReservations();
       
        
        $reservation = new Reservations();
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $isOverlap = false; // Variable pour indiquer s'il y a chevauchement
        
            // Vérifie le chevauchement avec les réservations existantes
            foreach ($reservation_exist as $element) {
                $existStartRent = $element->getStartRent();
                $existEndRent = $element->getEndRent();
        
                if ($reservation->getEndRent() > $existStartRent && $reservation->getStartRent() < $existEndRent) {
                    // Il y a chevauchement
                    $isOverlap = true;
                    break; // Pas besoin de vérifier d'autres réservations
                }
            }
        
            if (!$isOverlap) {
                // Aucun chevauchement, vous pouvez enregistrer la nouvelle réservation
                $reservation->setRental($this->getUser());
                $reservation->setGame($game);
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
            } else {
                
            }
        }
        
    

        return $this->render('reservations/new.html.twig', [
            'form' => $form,
            'reservation' => $reservation_exist,
            'categories' => $categories->findAll(),
            'boardgame'=>$boardgame,
        ]);
    }

    #[Route('/{id}', name: 'app_reservations_show', methods: ['GET'])]
    public function show(Reservations $reservation): Response
    {
        return $this->render('reservations/show.html.twig', [
            'reservation' => $reservation,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservations/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservations_delete', methods: ['POST'])]
    public function delete(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
    }
}
