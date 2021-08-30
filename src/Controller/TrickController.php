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
use Symfony\Component\HttpFoundation\JsonResponse;

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
            return $this->redirectToRoute('trick_show',[
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
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
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid() ){
            
            //$uploadFile->unlinkPictures($form, $trick);
            $uploadFile->uploadPictures($form, $trick);                       
            $entityManager->flush() ;
            $this->addFlash('success', 'trick modified with success');            
            return $this->redirectToRoute('trick_show',[
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }


        return $this->render('trick/edit3.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * 
     *
     * @Route("/delete/image/{id}", name="picture_remove_image", methods={"DELETE"})
     */
    public function removeImage(Picture $picture, Request $request, EntityManagerInterface $entityManager)
    {    
        $nom = $picture->getFilename();
        unlink($this->getParameter('images_directory').'/'.$nom);
        $entityManager->remove($picture);
        $entityManager->flush(); 
        return new JsonResponse(['success' => 1]);
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
       foreach ($pictures as $picture) {
            unlink($this->getParameter('images_directory').'/'.$picture->getFilename());
       }
       $entityManager->remove($trick);
       $entityManager->flush();
       $this->addFlash('success', 'trick deleted with success');
       return $this->redirectToRoute('home');
    }
}
