<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BurgerController extends AbstractController
{
    #[Route('/burgers', name: 'burger_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $burgers = $entityManager->getRepository(Burger::class)->findAll();
        return $this->render('list.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burger/create', name: 'burger_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $burger->setName('Krabby Patty');
        $burger->setPrice(4.99);
        $entityManager->persist($burger);
        $entityManager->flush();
        return new Response('Burger créé avec succès !');
    }

    #[Route('/burgers/ingredient/{name}', name: 'burger_by_ingredient')]
    public function byIngredient(string $name, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithIngredient($name);
        return $this->render('list.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burgers/top/{limit}', name: 'burger_top', requirements: ['limit' => '\d+'])]
    public function top(int $limit, BurgerRepository $burgerRepository): Response
    {
        // petite protection sur la valeur limite
        $limit = max(1, min(100, $limit));
        $burgers = $burgerRepository->findTopXBurgers($limit);
        return $this->render('list.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burgers/without/{type}/{id}', name: 'burger_without_ingredient')]
    public function burgersWithoutIngredient(string $type, int $id, EntityManagerInterface $entityManager, BurgerRepository $burgerRepository): Response
    {
        // Récupération de l'ingrédient selon le type
        $ingredient = null;
        switch ($type) {
            case 'sauce':
                $ingredient = $entityManager->getRepository('App\Entity\Sauce')->find($id);
                break;
            case 'oignon':
                $ingredient = $entityManager->getRepository('App\Entity\Oignon')->find($id);
                break;
            case 'pain':
                $ingredient = $entityManager->getRepository('App\Entity\Pain')->find($id);
                break;
            default:
                throw $this->createNotFoundException('Type d\'ingrédient inconnu');
        }
        if (!$ingredient) {
            throw $this->createNotFoundException('Ingrédient non trouvé');
        }

        $burgers = $burgerRepository->findBurgersWithoutIngredient($ingredient);
        return $this->render('list.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burgers/minimum/{min}', name: 'burger_minimum_ingredients', requirements: ['min' => '\d+'])]
    public function burgersWithMinimumIngredients(int $min, BurgerRepository $burgerRepository): Response
    {
        $min = max(1, $min);
        $burgers = $burgerRepository->findBurgersWithMinimumIngredients($min);
        return $this->render('list.html.twig', [
            'burgers' => $burgers,
        ]);
    }
}
