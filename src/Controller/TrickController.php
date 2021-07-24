<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use DateTime;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks/{category_slug}/{slug}", name="trick_show")
     */
    public function display($slug, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBy(['slug'=> $slug]);
        return $this->render('trick/display_trick.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/create", name="trick_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response 
    {
        $trick = new Trick;
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $trick
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
                ->setSlug(strtolower($slugger->slug($trick->getName())))
            ;

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'trick created with success');
            
            return $this->redirectToRoute('trick_create');
        }

        return $this->render('trick/create_trick.html.twig', [
            'formView' => $form->createView()
        ]);

        
    }
}
