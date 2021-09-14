<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )                
                )
                ->setRoles(["ROLE_USER"])
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
                //generation token d'activation
                ->setValidationToken(md5(uniqid()));
            ;          

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
                        
            $validationLink = $this->generateUrl('activation_token', ["token" => $user->getValidationToken() ], UrlGeneratorInterface::ABSOLUTE_URL);            
            $email = (new Email())
                ->from('admin@snowtricks.com')
                ->to($user->getEmail())
                ->subject('account validation link')
                ->text("cliquez sur le lien:  $validationLink")
            ;            
            $mailer->send($email);
            $this->addFlash('success', 'compte a été créé avec succés. Un mail vous a été envoyé pour valider votre compte');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activation/{token}", name="activation_token")
     */
    public function activationToken($token, UserRepository $userRepository){
        $user = $userRepository->findOneBy(['validation_token' => $token]);
        if(!$user){
            throw $this->createNotFoundException("cet utilisateur n'existe pas");
        }
        $user->setValidationToken(null);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'vous avez bien validé votre compte');
        return $this->redirectToRoute('home');
    }
}
