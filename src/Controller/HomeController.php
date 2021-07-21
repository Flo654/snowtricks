<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homepage(): Response
    {
        return $this->render('home/index.html.twig', [
            'page' => 1
        ]);
    }

     /**
     * @Route("/tricks/{page}", name="paginated_tricks")
     */
    public function paginated(TrickRepository $trickRepository, $page=1, $limit=6): Response
    {
        $tricks = $trickRepository->findBy([],[], $limit, ($limit *($page - 1)));
        return $this->render('shared/_trickList.html.twig',[
            'tricks' => $tricks,
            'page' => $page
        ]);
    }
}
