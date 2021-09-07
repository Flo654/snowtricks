<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\ForgotPasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
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

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset", name="reset")
     */
    public function requestResetLink( UserRepository $userRepository, Request $request, LoginLinkHandlerInterface $loginLinkHandler, MailerInterface $mailer)
    {
        $user = new User;        
        $form = $this->createForm( ForgotPasswordFormType::class , $user)->handleRequest($request);
        if ($request->isMethod('POST')) {
            $email = $user->getEmail();
            $userp = $userRepository->findOneBy(['email' => $email]);
            $loginLinkDetails = $loginLinkHandler->createLoginLink($userp);
            $loginLink = $loginLinkDetails->getUrl();
            $emaile = (new Email())
                ->from('hello@example.com')
                ->to($email)
                ->text($loginLink)
            ;            
            $mailer->send($emaile);
            $this->addFlash('success', 'message envoyé');
            return $this->redirectToRoute('home');
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
