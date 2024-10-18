<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\IntraController;

class MainController extends AbstractController
{

    #[Route('/', name: 'app_main')]
    public function index(IntraController $intra): Response
    {
        if($intra->completeCoordonnees($this->getUser()))
        {
           $this->addFlash('alert-warning','Vous devez compléter les coordonnées de votre compte');
           return $this->redirectToRoute('app_civility_register');
        }


        return $this->render('main/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }




}
