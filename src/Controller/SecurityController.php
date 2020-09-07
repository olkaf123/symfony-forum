<?php
/**
 * Security controller.
 */

namespace App\Controller;

use App\Entity\Details;
use App\Entity\User;
use App\Form\UserType;
use App\Service\DetailsService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class CategoryController.
 */
class SecurityController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * Details service.
     *
     * @var \App\Service\DetailsService
     */
    private $detailsService;

    /**
     * SecurityController constructor.
     *
     * @param \App\Service\UserService    $userService    User service
     * @param \App\Service\DetailsService $detailsService Details service
     */
    public function __construct(UserService $userService, DetailsService $detailsService)
    {
        $this->detailsService = $detailsService;
        $this->userService = $userService;
    }

    /**
     * Login
     *
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Create action.
     *
     * @param Request                      $request HTTP request
     * @param UserPasswordEncoderInterface $encoder Password encoder
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="app_register",
     * )
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles([User::ROLE_USER]);
            $this->userService->save($user);
            $details = new Details();
            $details->setName($form['name']->getData());
            $details->setSurname($form['surname']->getData());
            $details->setUser($user);
            $this->detailsService->save($details);
            $this->addFlash('success', 'message.created.successfully');

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'security/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/edit",
     *     methods={"GET", "POST"},
     *     name="app_edit",
     * )
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $details = $user->getDetails();
            $details->setName($form['name']->getData());
            $details->setSurname($form['surname']->getData());
            $this->detailsService->save($details);
            $this->addFlash('success', 'message.updated.successfully');

            return $this->redirectToRoute('app_edit');
        }

        return $this->render(
            'security/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Password action.
     *
     * @param Request                      $request HTTP request
     * @param UserPasswordEncoderInterface $encoder Password encoder
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/password",
     *     methods={"GET", "POST"},
     *     name="app_password",
     * )
     */
    public function password(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('name');
        $form->remove('surname');
        $form->remove('email');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $this->userService->save($user);
            $this->addFlash('success', 'message.updated.successfully');

            return $this->redirectToRoute('app_password');
        }

        return $this->render(
            'security/password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
