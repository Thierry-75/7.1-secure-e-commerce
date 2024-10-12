<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\JwtService;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, MailService $mail, JwtService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword))
                 ->setRoles(['ROLE_USER']);

            try{
                $em->persist($user);
                $em->flush();
            }catch (EntityNotFoundException $e){
                return $this->redirectToRoute('app_error',['exception'=>$e]);
            }
            // gneration jeton
            $header = ['typ'=>'JWT','alg'=>'HS256'];
            $payload = ['user_id'=>$user->getId()];
            $token = $jwt->generate($header,$payload,$this->getParameter('app.jwtsecret'));
            //envoi mail
            $mail->sendMail('no-reply@e-commerce.com', $user->getEmail(),'Activation de votre compte','register',['user'=>$user,'token'=>$token]);
            $this->addFlash('alert-success','confirmer votre adresse courriel');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
