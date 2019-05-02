<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AdminCommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Permets d'afficher la liste des commentaires
     * 
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     * 
     * @return Response
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setLimit(5)
                   ->setPage($page);

        return $this->render('admin/comments/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permets d'éditer un commentaire 
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     * 
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire n°{$comment->getId()} a bien été modifié"
            );
        }

        return $this->render('admin/comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permets de supprimer un commentaires
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     * 
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $manager->remove($comment); // REMOVE PAS PERSIST QUAND TU SUPPRIME
        $manager->flush();
        
        $this->addFlash(
            'success',
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimé"
        );

        return $this->redirectToRoute('admin_comment_index');
    }
}
