<?php

namespace App\Controller;

use App\Entity\Sauce;
use App\Form\SauceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SauceController extends AbstractController
{
    #[Route('/sauces', name: 'sauce_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sauces = $entityManager->getRepository(\App\Entity\Sauce::class)->findAll();
        return $this->render('sauce/index.html.twig', [
            'sauces' => $sauces,
        ]);
    }

    #[Route('/sauce/create', name: 'sauce_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $sauce = new \App\Entity\Sauce();
        $sauce->setName('Sauce BBQ');
        $entityManager->persist($sauce);
        $entityManager->flush();
        return new Response('Sauce créée avec succès !');
    }

    #[Route('/sauce/new', name: 'sauce_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sauce = new Sauce();
        $form = $this->createForm(SauceType::class, $sauce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sauce);
            $entityManager->flush();

            return $this->redirectToRoute('sauce_index');
        }

        return $this->render('sauce/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sauce/{id}', name: 'sauce_show')]
    public function show($id, EntityManagerInterface $entityManager): Response
    {
        $sauce = $entityManager->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }
        return $this->render('sauce/show.html.twig', [
            'sauce' => $sauce,
        ]);
    }

    #[Route('/sauce/{id}/edit', name: 'sauce_edit')]
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sauce = $entityManager->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }
        $form = $this->createForm(SauceType::class, $sauce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sauce);
            $entityManager->flush();

            return $this->redirectToRoute('sauce_index');
        }

        return $this->render('sauce/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sauce/{id}/delete', name: 'sauce_delete')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $sauce = $entityManager->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }
        $entityManager->remove($sauce);
        $entityManager->flush();

        return new Response('Sauce supprimée avec succès !');
    }
}
