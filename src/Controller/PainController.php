<?php

namespace App\Controller;

use App\Entity\Pain;
use App\Form\PainType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PainController extends AbstractController
{
    #[Route('/pains', name: 'pain_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pains = $entityManager->getRepository(\App\Entity\Pain::class)->findAll();
        return $this->render('pain/index.html.twig', [
            'pains' => $pains,
        ]);
    }

    #[Route('/pain/create', name: 'pain_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $pain = new \App\Entity\Pain();
        $pain->setName('Pain complet');
        $entityManager->persist($pain);
        $entityManager->flush();
        return new Response('Pain créé avec succès !');
    }

    #[Route('/pain/new', name: 'pain_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pain = new Pain();
        $form = $this->createForm(PainType::class, $pain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pain);
            $entityManager->flush();

            return $this->redirectToRoute('pain_index');
        }

        return $this->render('pain/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pain/{id}', name: 'pain_show')]
    public function show(Pain $pain): Response
    {
        return $this->render('pain/show.html.twig', [
            'pain' => $pain,
        ]);
    }

    #[Route('/pain/{id}/edit', name: 'pain_edit')]
    public function edit(Pain $pain, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PainType::class, $pain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pain);
            $entityManager->flush();

            return $this->redirectToRoute('pain_index');
        }

        return $this->render('pain/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pain/{id}/delete', name: 'pain_delete')]
    public function delete(Pain $pain, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($pain);
        $entityManager->flush();
        return new Response('Pain supprimé avec succès !');
    }
}
