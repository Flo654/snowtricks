<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/{trickId}/comments/{page}", name="paginated_comments")
     */
    public function paginatedComments($trickId, CommentRepository $commentRepository ,$page= 1, $limit = 3): Response
    {
        $comments = $commentRepository
            ->findBy(
                ['trick' => $trickId],
                ['createdAt' => 'DESC'], 
                $limit, 
                ($limit *($page - 1))
            )
        ;
        
        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
            'nbOfPage' => ceil(count($commentRepository->findBy(['trick' => $trickId]))/$limit),
            'page' => $page
        ]);
    }

    
}
