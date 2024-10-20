<?php

namespace App\Controller;

use App\Entity\Civility;
use App\Entity\User;
use App\Form\CivilityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Message\SendNotification;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\IntraController;

;

class CivilityController extends AbstractController
{
    #[Route('/civility/register', name: 'app_civility_register', methods: ['GET', 'POST'])]
    public function register(Request $request,
     EntityManagerInterface $em,
     ValidatorInterface $validator,
     MessageBusInterface $bus,
     IntraController $intra): Response
    {
       
        if($this->denyAccessUnlessGranted('ROLE_USER')){
            $this->addFlash('alert-danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        if($intra->confirmationEmail($this->getUser()))
        {
            $this->addFlash('alert-warning', 'Ce compte est déjà activé !');
            return $this->redirectToRoute('app_main'); 
        }
        $civility = new Civility();
        $user = $this->getUser();
        $form_register = $this->createForm(CivilityFormType::class, $civility);
        $form_register->handleRequest($request);
        if ($request->isMethod('POST')) {
            $errors = $validator->validate($civility);
            if (count($errors) > 0) {
                return $this->render('/civility/register.html.twig', ['form_register' => $form_register->createView(), 'errors' => $errors]);
            }
            if ($form_register->isSubmitted() && $form_register->isValid()) {
                $civility->setClient($this->getUser());
                $client = $em->getRepository((User::class))->find($user);
                $client->setCompleted(true);
                $em->persist($client);
                $em->persist($civility);
                $em->flush();
                $bus->dispatch(new SendNotification('Vos coordonnées ont bien été reçues',$client->getEmail()));
               // $mailer->sendMail('webmaster@e-commerce.com', $client->getEmail(),'Confirmation de vos coordonnées','confirmation',['user'=>$user,'token'=>'']);  
                $this->addFlash('alert-success', 'Vos coordonnées ont été enregistrées !');
                return $this->redirectToRoute('app_main'); // vers profile ?
            }
        }
        return $this->render('/civility/register.html.twig', ['form_register' => $form_register->createView()]);
    }

    
    /*---------------------------------------------------------------------------------------------------*/
    #[Route('civility/update/{id}',name:'app_civility_update',methods:['GET','POST'])]
    public function updateUser(Civility $civility): Response
    {
    if($this->denyAccessUnlessGranted('ROLE_GESTION')){
        $this->addFlash('alert-danger','Accès interdit');
        return $this->redirectToRoute('app_main');
    }
    return $this->render('civility/update.html.twig');
}

    #[Route('civility/delete/{id}',name:'app_civility_delete',methods:['GET','POST'])]
        public function deleteUser(Civility $civility): Response
        {
        if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            $this->addFlash('alert-danger','Accès interdit');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('civility/delete.html.twig');
    }
}
