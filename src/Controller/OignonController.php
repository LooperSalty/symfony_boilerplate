<?php

namespace App\Controller;

use App\Entity\Oignon;
use App\Form\OignonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OignonController extends AbstractController
{
    #[Route('/oignons', name: 'oignon_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $oignons = $entityManager->getRepository(\App\Entity\Oignon::class)->findAll();
        return $this->render('oignon/index.html.twig', [
            'oignons' => $oignons,
        ]);
    }

    #[Route('/oignon/create', name: 'oignon_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $oignon = new \App\Entity\Oignon();
        $oignon->setName('Oignon rouge');
        $entityManager->persist($oignon);
        $entityManager->flush();
        return new Response('Oignon créé avec succès !');
    }

    #[Route('/oignon/new', name: 'oignon_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oignon = new Oignon();
        $form = $this->createForm(OignonType::class, $oignon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oignon);
            $entityManager->flush();

            return $this->redirectToRoute('oignon_index');
        }

        return $this->render('oignon/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/oignon/{id}', name: 'oignon_show')]
    public function show(Oignon $oignon): Response
    {
        return $this->render('oignon/show.html.twig', [
            'oignon' => $oignon,
        ]);
    }

    #[Route('/oignon/{id}/edit', name: 'oignon_edit')]
    public function edit(Oignon $oignon, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OignonType::class, $oignon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oignon);
            $entityManager->flush();

            return $this->redirectToRoute('oignon_index');
        }

        return $this->render('oignon/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/oignon/{id}/delete', name: 'oignon_delete')]
    public function delete(Oignon $oignon, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($oignon);
        $entityManager->flush();
        return new Response('Oignon supprimé avec succès !');
    }
}
