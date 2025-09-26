<?php
namespace App\Controller;

use App\Entity\Oignon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
