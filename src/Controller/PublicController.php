<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Status;
use App\Form\PublicTicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'public_home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $ticket->setOpenedAt(new \DateTimeImmutable());

        $form = $this->createForm(PublicTicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // statut par défaut : "Nouveau"
            $statusRepo = $entityManager->getRepository(Status::class);
            $defaultStatus = $statusRepo->findOneBy(['name' => 'Nouveau']);

            if ($defaultStatus !== null) {
                $ticket->setStatus($defaultStatus);
            }

            $entityManager->persist($ticket);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ticket a été enregistré.');

            return $this->redirectToRoute('public_home');
        }

        return $this->render('public/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
