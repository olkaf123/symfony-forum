<?php
/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController.
 *
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     * Show action.
     *
     * @param Post $post Post entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="post_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Post $post): Response
    {
        $comments = [];         //paginacja
        $mark = 5;              //funkcja do obliczania oceny
        $alreadyMarked = false; //funkcja sprawdzająca czy user już oceniał

        return $this->render(
            'posts/show.html.twig',
            [
                'post' => $post,
                'comments' => $comments,
                'mark' => $mark,
                'alreadyMarked' => $alreadyMarked,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request        $request        HTTP request
     * @param Category       $category       Category
     * @param PostRepository $postRepository Post repository
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
     *     name="post_create",
     * )
     */
    public function create(Request $request, Category $category, PostRepository $postRepository, UserRepository $userRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $user = $userRepository->find(1); //zamienić na zalogowanego

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCategory($category);
            $post->setUser($user);
            $postRepository->save($post);
            $this->addFlash('success', 'message.created.successfully');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render(
            'posts/create.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Post                          $post           Post entity
     * @param \App\Repository\PostRepository            $postRepository Post repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="post_edit",
     * )
     */
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post);
            $this->addFlash('success', 'message.updated.successfully');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render(
            'posts/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request        $request        HTTP request
     * @param Post           $post           Post entity
     * @param PostRepository $postRepository Post repository
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
     *     name="post_delete",
     * )
     */
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(FormType::class, $post, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $post->getCategory();
            $postRepository->delete($post);
            $this->addFlash('success', 'message.deleted.successfully');

            return $this->redirectToRoute('category_show', ['id' => $category->getId()]);
        }

        return $this->render(
            'posts/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }
}