<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;
    private $passwordEncoder;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request,ValidatorInterface $validator)
    {
        $user = new User();

        $user->setRoles([$request->get('role')]);
        $user->setEmail($request->get('email'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->get('password')));
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Route("/{id}", name="get")
     */
    public function  getById(User $user)
    {
        return $this->view($user);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(User $user, Request $request)
    {
        $user->setLastname($request->get('lastname') ?? $user->getLastname());
        $user->setFirstname($request->get('firstname') ?? $user->getFirstname());
        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->get('password')?? $user->getPassword()));
        $user->setEmail($request->get('email') ?? $user->getEmail());
        $user->setRoles($request->get('roles') ?? $user->getRoles());
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
 /**    
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}