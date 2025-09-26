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
    #[Route('/burgers', name: 'app_burgers_list')]
    public function list(): Response
    {
        return $this->render('list.html.twig');
    }

    public function show(int $id): Response
    {
        return $this->render('', ['id' => $id,]);
    }
}
