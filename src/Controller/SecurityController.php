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
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
                  

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
            if(!$userp){
                
               $this->addFlash('fail', "cette adresse n'existe pas dans la base de données!");
                return $this->redirectToRoute('reset');
            }
            $loginLinkDetails = $loginLinkHandler->createLoginLink($userp);
            $loginLink = $loginLinkDetails->getUrl();
            $emaile = (new Email())
                ->from('admin@snowtricks.com')
                ->to($email)
                ->subject('reset_password link')
                ->text("cliquez sur le lien:  $loginLink")
            ;            
            $mailer->send($emaile);
            $this->addFlash('success', "un message a été envoyé à l'adresse $email");
            return $this->redirectToRoute('home');
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
