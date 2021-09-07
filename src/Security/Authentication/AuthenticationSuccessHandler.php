<?php
namespace App\Security\Authentication;

use DateTime;
use Twig\Environment;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler extends AbstractController implements AuthenticationSuccessHandlerInterface 
{
    private $userRepository;
    private $userPasswordHasher;

    public function __construct(userRepository $userRepository, FormFactoryInterface $formFactoryInterface, Environment $twig, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;        
    }    
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $email = $request->query->get('user');
        $user = $this->userRepository->findOneBy(['email' => $email]);
        $form = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user
                ->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )                
                )                
                ->setUpdatedAt(new DateTime('NOW'))
            ;
            $entityManager = $this->getDoctrine()->getManager();            
            $entityManager->flush();
            $this->addFlash('success', 'mot de passe changé');            
            return $this->redirectToRoute('home');
        }
        return $this->render('reset_password/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}