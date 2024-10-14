<?php

namespace App\Controller;

use App\Entity\Civility;
use App\Form\CivilityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CivilityController extends AbstractController
{
    #[Route('/civility/register', name: 'app_civility_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $em,  ValidatorInterface $validator): Response
    {
        $user = $this->getUser();
        //dd($user);
        if (!$user) {
            $this->addFlash('alert-danger', 'Vous devez être connecté pour accéder à cette page !');
            return $this->redirectToRoute('app_login');
        }
        if (!$this->getUser()->isCompleted() == false) {
            $this->addFlash('alert-warning', 'Ce compte est déjà activé !');
            return $this->redirectToRoute('app_main');  // redirect to profil
        }
        $civility = new Civility();
        $form_register = $this->createForm(CivilityFormType::class, $civility);
        $form_register->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($civility);
            if (count($errors) > 0) {
                return $this->render('/civility/register.html.twig', ['form_register' => $form_register->createView(), 'errors' => $errors]);
            }
            if ($form_register->isSubmitted() && $form_register->isValid()) {
                $civility->setClient($this->getUser());
                $em->persist($civility);
                $em->flush();
                $this->addFlash('alert-success', 'Vos coordonnées ont été enregistrées !');
                return $this->redirectToRoute('app_main'); // vers profile ?
            }
        }
        return $this->render('/civility/register.html.twig', ['form_register' => $form_register->createView()]);
    }
}
