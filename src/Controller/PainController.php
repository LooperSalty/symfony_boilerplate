<?php
namespace App\Controller;

use App\Entity\Pain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
