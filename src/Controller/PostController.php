<?php
/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostMark;
use App\Form\PostType;
use App\Repository\CommentMarkRepository;
use App\Service\CommentService;
use App\Service\PostMarkService;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class PostController.
 *
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     * Commnet service.
     *
     * @var \App\Service\CommentService
     */
    private $commentService;

    /**
     * Post mark service.
     *
     * @var \App\Service\PostmarkService
     */
    private $postMarkService;

    /**
     * Post service.
     *
     * @var \App\Service\PostmarkService
     */
    private $postService;

    /**
     * PostController constructor.
     *
     * @param \App\Service\CommentService  $commentService  Category service
     * @param \App\Service\PostMarkService $postMarkService Post mark service
     * @param \App\Service\PostService     $postService     Post service
     */
    public function __construct(CommentService $commentService, PostMarkService $postMarkService, PostService $postService)
    {
        $this->commentService = $commentService;
        $this->postMarkService = $postMarkService;
        $this->postService = $postService;
    }

    /**
     * Show action.
     *
     * @param Request               $request               Request
     * @param Post                  $post                  Post entity
     * @param CommentMarkRepository $commentMarkRepository Comment Mark Repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="post_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Request $request, Post $post, CommentMarkRepository $commentMarkRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $comments = $this->commentService->createPaginatedList($page, $post);

        $mark = $this->postMarkService->countMarkValue($post);
        $user = $this->getUser();
        $alreadyMarked = $this->postMarkService->alreadyVoted($post, $user);

        return $this->render(
            'posts/show.html.twig',
            [
                'post' => $post,
                'comments' => $comments,
                'mark' => $mark,
                'alreadyMarked' => $alreadyMarked,
                'commentMarkRepository' => $commentMarkRepository,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category
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
    public function create(Request $request, Category $category): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCategory($category);
            $post->setUser($user);
            $this->postService->save($post);
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
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
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
     *     name="post_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="post",
     * )
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);
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
     * @param Request $request HTTP request
     * @param Post    $post    Post entity
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
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="post",
     * )
     */
    public function delete(Request $request, Post $post): Response
    {
        $form = $this->createForm(FormType::class, $post, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $post->getCategory();
            $this->postService->delete($post);
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

    /**
     * Mark action.
     *
     * @param Post $post Post entity
     * @param int  $bool boolean
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
     *     name="post_mark",
     *     requirements={"id": "[1-9]\d*", "bool": "0|1"},
     * )
     */
    public function mark(Post $post, int $bool): Response
    {
        $user = $this->getUser();
        $alreadyMarked = $this->postMarkService->alreadyVoted($post, $user);
        if ($alreadyMarked) {
            $this->addFlash('danger', 'message.permission.denied');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        $mark = new PostMark();
        $mark->setUser($user);
        $mark->setPost($post);
        $mark->setMark($bool ? 1 : -1);
        $this->postMarkService->save($mark);
        $this->addFlash('success', 'message.added.successfully');

        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }
}
