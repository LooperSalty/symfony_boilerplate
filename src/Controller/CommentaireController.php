<?php
namespace App\Controller;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    #[Route('/commentaires', name: 'commentaire_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager->getRepository(\App\Entity\Commentaire::class)->findAll();
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/commentaire/create', name: 'commentaire_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $commentaire = new \App\Entity\Commentaire();
        $commentaire->setName('Super burger !');
        $entityManager->persist($commentaire);
        $entityManager->flush();
        return new Response('Commentaire créé avec succès !');
    }
}
