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
}