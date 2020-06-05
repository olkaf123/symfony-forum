<?php

namespace App\Controller;

use App\Entity\Details;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\DetailsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
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
     * @param Request                      $request           HTTP request
     * @param UserRepository               $userRepository    User repository
     * @param DetailsRepository            $detailsRepository Details repository
     * @param UserPasswordEncoderInterface $encoder           Password encoder
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
    public function create(Request $request, UserRepository $userRepository, DetailsRepository $detailsRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setRoles([User::ROLE_USER]);
            $userRepository->save($user);
            $details = new Details();
            $details->setName($form['name']->getData());
            $details->setSurname($form['surname']->getData());
            $details->setUser($user);
            $detailsRepository->save($details);
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
     * @param Request                      $request           HTTP request
     * @param UserRepository               $userRepository    User repository
     * @param DetailsRepository            $detailsRepository Details repository
     * @param UserPasswordEncoderInterface $encoder           Password encoder
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
    public function edit(Request $request, UserRepository $userRepository, DetailsRepository $detailsRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $userRepository->save($user);
            $details = $user->getDetails();
            $details->setName($form['name']->getData());
            $details->setSurname($form['surname']->getData());
            $detailsRepository->save($details);
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
}
