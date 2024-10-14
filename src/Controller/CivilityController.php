<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CivilityController extends AbstractController
{
    #[Route('/civility', name: 'app_civility')]
    public function index(): Response
    {
        return $this->render('civility/index.html.twig', [
            'controller_name' => 'CivilityController',
        ]);
    }
}
