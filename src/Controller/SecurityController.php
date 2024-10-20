<?php

namespace App\Controller;

use App\Service\MailService;
use App\Message\SendNotification;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Message\SendReinitialisation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use App\Form\ResetPasswordRequestFormType;
use App\Message\SendConfirmationReset;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * form request reset password
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param TokenGeneratorInterface $tokenGeneratorInterface
     * @param EntityManagerInterface $em
     * @param MessageBusInterface $bus
     * @return Response
     */
    #[Route('/forget-passwd', name: 'Forgotten_password', methods: ['GET', 'POST'])]
    public function forgottenPassword(
        Request $request, 
        ValidatorInterface $validator, 
        UserRepository $userRepository, 
        TokenGeneratorInterface $tokenGeneratorInterface, 
        EntityManagerInterface $em, 
        MessageBusInterface $bus): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $error = $validator->validate(($request));
            if (count($error) > 0) {
                return $this->render('security/reset_password_request.html.twig', ['requestPassFrom' => $form->createView(), 'errors' => $error]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->findOneByEmail($form->get('email')->getData());
                if (isset($user)) {
                    //token
                    $token = $tokenGeneratorInterface->generateToken();
                    //try
                    try {
                        $user->setResetToken($token);
                        $em->persist($user);
                        $em->flush();
                    } catch (EntityNotFoundException $e) {
                        return $this->redirectToRoute('app_error');
                    }
                    //link 
                    $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                    //email
                    $context = ['url' => $url, 'user' => $user];
                    //$mail->sendMail('no-reply@e-commerce.com', $user->getEmail(), 'Réinitialisation du mot de passe', 'password_reset', $context);
                    $bus->dispatch(new SendReinitialisation('no-reply@e-commerce.com',$user->getEmail(),'Réinitialisation de votre mot de passe','password_reset',$context));
                    $this->addFlash('alert-success', 'email envoyé !');
                    return $this->redirectToRoute('app_main');
                }
                $this->addFlash('alert-danger', 'Un problème est survenu !');
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/reset_password_request.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/reset-pass/{token}', name: 'reset_pass', methods: ['GET', 'POST'])]
    public function resetPassword(
        string $token, 
        Request $request, 
        UserRepository $userRepository, 
        ValidatorInterface $validator, 
        EntityManagerInterface $em, 
        UserPasswordHasherInterface $passwordHasher,
        MessageBusInterface $bus)
    {
        //check jeton
        $user = $userRepository->findOneByResetToken($token);
        if (isset($user)) {
            $form_reset = $this->createForm(ResetPasswordFormType::class);
            $form_reset->handleRequest($request);
            if ($request->isMethod('POST')) {
                $error = $validator->validate($request);
                if (count($error) > 0) {
                    return $this->render('security/reset_password.html.twig', ['form_request_password' => $form_reset->createView(), 'errors' => $error]);
                }
                if ($form_reset->isSubmitted() && $form_reset->isValid()) {
                    $user->setResetToken('');
                    $user->setPassword(
                        $passwordHasher->hashPassword($user, $form_reset->get('password')->getData())
                    );
                    try {
                        $em->persist($user);
                        $em->flush();
                    } catch (EntityNotFoundException $e) {
                        return $this->redirectToRoute('app_error');
                    }
                    $bus->dispatch(new SendConfirmationReset('Votre mot de passe a bien été modifié',$user->getEmail()));
                    $this->addFlash('alert-success', 'Mot de passe changé avec succès !');
                    return $this->redirectToRoute('app_main');
                }
            }
            return $this->render('security/reset_password.html.twig', ['form_reset' => $form_reset->createView()]);
        }
        $this->addFlash('danger', 'Jeton invalide !');
        return $this->redirectToRoute('app_login');
    }
}
