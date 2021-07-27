<?php

namespace App\Controller;

use App\Entity\Picture;
use DateTime;
use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use App\Services\UploadFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks/{category_slug}/{slug}", name="trick_show")
     */
    public function display($slug, TrickRepository $trickRepository): Response
    {
        
        return $this->render('trick/display_trick.html.twig', [
            'trick' => $trickRepository->findOneBy(['slug'=> $slug])
        ]);
    }

    /**
     * @Route("/create", name="trick_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UploadFile $uploadFile): Response 
    {
        $trick = new Trick;
        
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $uploadFile->uploadPictures($form, $trick);            
            $trick
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
                ->setSlug(strtolower($slugger->slug($trick->getName())))              
            ;
            $entityManager->persist($trick);            
            $entityManager->flush() ;
            $this->addFlash('success', 'trick created with success');            
            return $this->redirectToRoute('trick_create');
        }

        return $this->render('trick/create_trick.html.twig', [
            'form' => $form->createView()
        ]);

        
    }

    
}
