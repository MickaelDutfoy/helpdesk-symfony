<?php

namespace App\Controller;

use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Form\StaffStatusType;

#[IsGranted('ROLE_STAFF')]
class StaffController extends AbstractController
{
    #[Route('/staff', name: 'staff_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tickets = $entityManager->getRepository(Ticket::class)->findAll();

        return $this->render('staff/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    #[Route('/staff/ticket/{id}/edit-status', name: 'staff_edit_status')]
    public function editStatus(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = $entityManager->getRepository(Ticket::class)->find($id);

        if (!$ticket) {
            throw $this->createNotFoundException('Ticket introuvable');
        }

        $form = $this->createForm(StaffStatusType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('staff_dashboard');
        }

        return $this->render('staff/edit_status.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
