<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    /**
     * Retourne une liste de Burger contenant l'ingrédient fourni.
     *
     * @return Burger[]
     */
    public function findBurgersWithIngredient(string $ingredient): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT b FROM App\Entity\Burger b JOIN b.oignons o WHERE o.name = :ingredient';
        return $em->createQuery($dql)
            ->setParameter('ingredient', $ingredient)
            ->getResult();
    }

    /**
     * Retourne les $limit burgers les plus chers.
     *
     * @return Burger[]
     */
    public function findTopXBurgers(int $limit): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT b FROM App\Entity\Burger b ORDER BY b.price DESC';
        return $em->createQuery($dql)
            ->setMaxResults($limit)
            ->getResult();
    }

    /**
     * Retourne les burgers qui NE contiennent PAS un ingrédient donné.
     * $ingredient doit être une entité (ex: Sauce, Oignon, Pain)
     */
    public function findBurgersWithoutIngredient(object $ingredient): array
    {
        $em = $this->getEntityManager();
        $ingredientClass = get_class($ingredient);

        // Détection du type d'ingrédient pour la jointure
        if ($ingredientClass === 'App\Entity\Sauce') {
            $relation = 'sauces';
            $alias = 's';
        } elseif ($ingredientClass === 'App\Entity\Oignon') {
            $relation = 'oignons';
            $alias = 'o';
        } elseif ($ingredientClass === 'App\Entity\Pain') {
            $relation = 'pains';
            $alias = 'p';
        } else {
            throw new \InvalidArgumentException('Type d\'ingrédient non supporté');
        }

        $dql = "SELECT b FROM App\Entity\Burger b
                LEFT JOIN b.$relation $alias
                WHERE $alias.id IS NULL OR $alias != :ingredient";
        // On vérifie que l'ingrédient n'est pas dans la collection
        return $em->createQuery($dql)
            ->setParameter('ingredient', $ingredient)
            ->getResult();
    }

    /**
     * Retourne les burgers ayant au moins $minIngredients ingrédients (toutes catégories confondues).
     * On suppose que Burger possède des relations sauces, oignons, pains.
     */
    public function findBurgersWithMinimumIngredients(int $minIngredients): array
    {
        $em = $this->getEntityManager();
        $dql = "SELECT b, (SIZE(b.sauces) + SIZE(b.oignons) + SIZE(b.pains)) AS totalIngredients
                FROM App\Entity\Burger b
                HAVING totalIngredients >= :minIngredients";
        return $em->createQuery($dql)
            ->setParameter('minIngredients', $minIngredients)
            ->getResult();
    }
}
