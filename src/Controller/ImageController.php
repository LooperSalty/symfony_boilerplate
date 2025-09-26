<?php
namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/images', name: 'image_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(\App\Entity\Image::class)->findAll();
        return $this->render('image/index.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/image/create', name: 'image_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $image = new \App\Entity\Image();
        $image->setName('https://via.placeholder.com/640x480.png?text=Burger');
        $entityManager->persist($image);
        $entityManager->flush();
        return new Response('Image créée avec succès !');
    }
}
