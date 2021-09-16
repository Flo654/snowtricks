<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Picture;
use DateTime;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Services\UploadFile;
use Doctrine\ORM\EntityManager;
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
use Symfony\Component\HttpFoundation\JsonResponse;

class TrickController extends AbstractController
{
    /**
     * @Route("/tricks/{category_slug}/{slug}", name="trick_show")
     */
    public function display($slug, TrickRepository $trickRepository, CommentRepository $commentRepository , EntityManagerInterface $entityManager, Request $request): Response
    {
        $comment = new Comment;
        $user = $this->getUser();
        $trick = $trickRepository->findOneBy(['slug'=> $slug]);
        
        $form = $this->createForm(CommentFormType::class, $comment)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment
                ->setCreatedAt(new DateTime('NOW'))
                ->setUpdatedAt(new DateTime('NOW'))
                ->setTrick($trick)
                ->setUser($user)
            ;
            $entityManager->persist($comment);            
            $entityManager->flush() ;
            $this->addFlash('success', 'message created with success');
        }

        return $this->render('trick/display_trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'nbOfPage' => ceil(count($commentRepository->findBy(['trick' => $trick->getId()]))/ Comment::LIMIT_COMMENT_DISPLAY),
            'page' => 1
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
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/create_trick.html.twig', [
            'form' => $form->createView()
        ]);

        
    }

    /**
     * Undocumented function
     *
     * @Route("/edit/{id}", name="trick_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Trick $trick, UploadFile $uploadFile, $id ) :Response
    {
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid() ){
                        
            $uploadFile->uploadPictures($form, $trick);                       
            $entityManager->flush() ;
            $this->addFlash('success', 'trick modified with success');            
            return $this->redirectToRoute('home');
        }


        return $this->render('trick/edit3.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * 
     *
     * @Route("/delete/{id}", name="trick_delete")
     */
    public function delete($id, TrickRepository $trickRepository, EntityManagerInterface $entityManager )
    {
       $trick =  $trickRepository->find($id);
       $pictures = $trick->getPictures()->getValues();
       $entityManager->remove($trick);
       $entityManager->flush();
       foreach ($pictures as $picture) {
            unlink($this->getParameter('images_directory').'/'.$picture->getFilename());
        }
       $this->addFlash('success', 'trick deleted with success');
       return $this->redirectToRoute('home');
    }
}
