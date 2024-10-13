<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function index($e): Response
    {
        return $this->render('/bundles/TwigBundle/Exception/error.html.twig',['exception'=>$e]);
    }
}
