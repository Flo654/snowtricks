<?php
namespace App\Controller;

use App\Entity\Video;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * 
     *
     * @Route("/delete/image/{id}", name="picture_remove_image", methods={"DELETE"})
     */
    public function removeImage(Picture $picture, EntityManagerInterface $entityManager)
    {    
        $nom = $picture->getFilename();
        $entityManager->remove($picture);
        $entityManager->flush();
        unlink($this->getParameter('images_directory').'/'.$nom);
        return new JsonResponse(['success' => 1]);
    }

    /**
     * 
     *
     * @Route("/delete/video/{id}", name="video_remove_link", methods={"DELETE"})
     */
    public function removeVideoLink(Video $video, EntityManagerInterface $entityManager)
    {
       
        $entityManager->remove($video);
        $entityManager->flush();
        return new JsonResponse(['success' => 1]);
    }
}