<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JwtService;
use App\Service\MailService;
use Doctrine\ORM\EntityManager;
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
            // generation jeton
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

    /**
     * isValid && isExpired && check
     */
    #[Route('/check/{token}',name: 'check_user')]
    public function CheckUser($token, JwtService $jwt, UserRepository $userRepository, EntityManagerInterface $em,): Response
    {
        if($jwt->isValid($token)  && !$jwt->isExpired($token)  && $jwt->check($token,$this->getParameter('app.jwtsecret'))){
            $payload = $jwt->getPayload($token);
            $user = $userRepository->find($payload['user_id']);
            if($user  && !$user->IsVerified()){
                $user->setVerified(true);
                try{
                    $em->persist($user);
                    $em->flush();
                }catch(EntityNotFoundException $e){
                    return $this->redirectToRoute('app_error', ['exception'=> $e]);
                }
                $this->addFlash('alert-success','Votre compte a été activé !');
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash('alert-danger','Token invalide !');
            return $this->redirectToRoute('app_login');
        }

    }
}
