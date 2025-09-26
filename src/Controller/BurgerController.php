<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BurgerController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/burgers', name: 'burger_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $burgers = $entityManager->getRepository(\App\Entity\Burger::class)->findAll();
        return $this->render('burger/index.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burger/create', name: 'burger_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $burger = new \App\Entity\Burger();
        $burger->setName('Krabby Patty');
        $burger->setPrice(4.99);
        $entityManager->persist($burger);
        $entityManager->flush();
        return new Response('Burger créé avec succès !');
    }
}
