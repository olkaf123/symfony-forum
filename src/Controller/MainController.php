<?php
/**
 * Main controller.
 */

namespace App\Controller;

use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController.
 *
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request            HTTP request
     * @param CategoryRepository $categoryRepository Category Repository
     * @param PaginatorInterface $paginator          Paginator Interface
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="main_index",
     * )
     */
    public function index(Request $request, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $categories = $pagination = $paginator->paginate(
            $categoryRepository->queryAll(),
            $request->query->getInt('page', 1),
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'web/index.html.twig',
            ['categories' => $categories]
        );
    }

//  /**
//   * Show action.
//   *
//   * @param \App\Entity\Task $task Task entity
//   *
//   * @return \Symfony\Component\HttpFoundation\Response HTTP response
//   *
//   * @Route(
//   *     "/{id}",
//   *     methods={"GET"},
//   *     name="task_show",
//   *     requirements={"id": "[1-9]\d*"},
//   * )
//   */
//  public function show(Task $task): Response
//  {
//    return $this->render(
//      'task/show.html.twig',
//      ['task' => $task]
//    );
//  }
//
//  /**
//   * Create action.
//   *
//   * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
//   * @param \App\Repository\TaskRepository            $taskRepository Task repository
//   *
//   * @return \Symfony\Component\HttpFoundation\Response HTTP response
//   *
//   * @throws \Doctrine\ORM\ORMException
//   * @throws \Doctrine\ORM\OptimisticLockException
//   *
//   * @Route(
//   *     "/create",
//   *     methods={"GET", "POST"},
//   *     name="task_create",
//   * )
//   */
//  public function create(Request $request, TaskRepository $taskRepository): Response
//  {
//    $task = new Task();
//    $form = $this->createForm(TaskType::class, $task);
//    $form->handleRequest($request);
//
//    if ($form->isSubmitted() && $form->isValid()) {
//      $taskRepository->save($task);
//      $this->addFlash('success', 'message_created_successfully');
//
//      return $this->redirectToRoute('task_index');
//    }
//
//    return $this->render(
//      'task/create.html.twig',
//      ['form' => $form->createView()]
//    );
//  }
//
//  /**
//   * Edit action.
//   *
//   * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
//   * @param \App\Entity\Task                          $task           Task entity
//   * @param \App\Repository\TaskRepository            $taskRepository Task repository
//   *
//   * @return \Symfony\Component\HttpFoundation\Response HTTP response
//   *
//   * @throws \Doctrine\ORM\ORMException
//   * @throws \Doctrine\ORM\OptimisticLockException
//   *
//   * @Route(
//   *     "/{id}/edit",
//   *     methods={"GET", "PUT"},
//   *     requirements={"id": "[1-9]\d*"},
//   *     name="task_edit",
//   * )
//   */
//  public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
//  {
//    $form = $this->createForm(TaskType::class, $task, ['method' => 'PUT']);
//    $form->handleRequest($request);
//
//    if ($form->isSubmitted() && $form->isValid()) {
//      $taskRepository->save($task);
//      $this->addFlash('success', 'message_updated_successfully');
//
//      return $this->redirectToRoute('task_index');
//    }
//
//    return $this->render(
//      'task/edit.html.twig',
//      [
//        'form' => $form->createView(),
//        'task' => $task,
//      ]
//    );
//  }
//
//  /**
//   * Delete action.
//   *
//   * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
//   * @param \App\Entity\Task                          $task           Task entity
//   * @param \App\Repository\TaskRepository            $taskRepository Task repository
//   *
//   * @return \Symfony\Component\HttpFoundation\Response HTTP response
//   *
//   * @throws \Doctrine\ORM\ORMException
//   * @throws \Doctrine\ORM\OptimisticLockException
//   *
//   * @Route(
//   *     "/{id}/delete",
//   *     methods={"GET", "DELETE"},
//   *     requirements={"id": "[1-9]\d*"},
//   *     name="task_delete",
//   * )
//   */
//  public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
//  {
//    $form = $this->createForm(FormType::class, $task, ['method' => 'DELETE']);
//    $form->handleRequest($request);
//
//    if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
//      $form->submit($request->request->get($form->getName()));
//    }
//
//    if ($form->isSubmitted() && $form->isValid()) {
//      $taskRepository->delete($task);
//      $this->addFlash('success', 'message_deleted_successfully');
//
//      return $this->redirectToRoute('task_index');
//    }
//
//    return $this->render(
//      'task/delete.html.twig',
//      [
//        'form' => $form->createView(),
//        'task' => $task,
//      ]
//    );
//  }
}