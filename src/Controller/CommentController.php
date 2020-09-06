<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\CommentMark;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentMarkRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 *
 * @Route("/comments")
 */
class CommentController extends AbstractController
{
    /**
     * Create action.
     *
     * @param Request           $request           HTTP request
     * @param Post              $post              Post
     * @param CommentRepository $commentRepository Comment repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_create",
     * )
     */
    public function create(Request $request, Post $post, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setUser($user);
            $commentRepository->save($comment);
            $this->addFlash('success', 'message.created.successfully');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render(
            'comments/create.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request           $request           HTTP request
     * @param Comment           $comment           Comment entity
     * @param CommentRepository $commentRepository Comment repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_edit",
     * )
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment);
            $this->addFlash('success', 'message.updated.successfully');

            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render(
            'comments/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request           $request           HTTP request
     * @param Comment           $comment           Comment entity
     * @param CommentRepository $commentRepository Comment repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $comment->getPost();
            $commentRepository->delete($comment);
            $this->addFlash('success', 'message.deleted.successfully');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render(
            'comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Mark action.
     *
     * @param Comment               $comment               Comment entity
     * @param int                   $bool                  boolean
     * @param CommentMarkRepository $commentMarkRepository Comment Mark Repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/mark/{bool}",
     *     methods={"GET"},
     *     name="comment_mark",
     *     requirements={"id": "[1-9]\d*", "bool": "0|1"},
     * )
     */
    public function mark(Comment $comment, int $bool, CommentMarkRepository $commentMarkRepository): Response
    {
        $user = $this->getUser();
        $alreadyMarked = $commentMarkRepository->alreadyVoted($comment, $user);
        if ($alreadyMarked) {
            $this->addFlash('danger', 'message.permission.denied');

            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        $mark = new CommentMark();
        $mark->setUser($user);
        $mark->setComment($comment);
        $mark->setMark($bool ? 1 : -1);
        $commentMarkRepository->save($mark);
        $this->addFlash('success', 'message.added.successfully');

        return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }
}
