<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
