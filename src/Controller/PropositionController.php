<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Proposition;
use App\Entity\Modification;
use App\Form\CommentType;
use App\Form\PropositionType;
use App\Manager\ApprovalManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proposition")
 */
class PropositionController extends AbstractController
{
    /**
     * @Route("/", name="proposition_index", methods={"GET"})
     */
    public function index(): Response
    {
        $propositions = $this->getDoctrine()
            ->getRepository(Proposition::class)
            ->findAll();

        return $this->render('proposition/index.html.twig', [
            'propositions' => $propositions,
        ]);
    }

    /**
     * @Route("/new", name="proposition_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request/*FileUploader $fileUploader*/): Response
    {
        $proposition = new Proposition();
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
           /* if ($file) {
                $fileName = $fileUploader->upload($file);
                $proposition->setFile($fileName);
            }
*/
            $date = new \DateTime();
            $proposition->setSubmitDate($date);

            $proposition->setUserSubmit($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposition);
            $entityManager->flush();

            return $this->redirectToRoute('proposition_index');
        }

        return $this->render('proposition/new.html.twig', [
            'proposition' => $proposition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposition_show", methods={"GET","POST"})
     * @param Proposition $proposition
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function show(Proposition $proposition, Request $request,EntityManagerInterface $entityManager): Response
    {
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(
                [
                    'proposition' => $proposition,
                ]
            );

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setProposition($proposition);
            $comment->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('proposition_show', ['id'=>$proposition->getId()]);
        }

        return $this->render('proposition/show.html.twig', [
            'comment_form' => $commentForm->createView(),
            'proposition' => $proposition,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proposition_edit", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Proposition $proposition
     
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, EntityManagerInterface $em, Proposition $proposition/*, FileUploader $fileUploader*/): Response
    {
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            if ($file) {
           /*     $fileName = $fileUploader->upload($file);
                $proposition->setFile($fileName);*/
            }

            $this->getDoctrine()->getManager()->flush();

            $modification = new Modification();
            $date = new \DateTime;
            $date = $date->format('Y-m-d H:i:s');
            $modification->setDateModification($date);
            $modification->setUser($this->getUser());
            $modification->setProposition($proposition);
            $modification->setContent($proposition->getContent());
            $em->persist($modification);
            $em->flush();

            return $this->redirectToRoute('proposition_index');
        }

        return $this->render('proposition/edit.html.twig', [
            'proposition' => $proposition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposition_delete", methods={"DELETE"})
     * @param Request $request
     * @param Proposition $proposition
     * @return Response
     */
    public function delete(Request $request, Proposition $proposition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proposition->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proposition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proposition_index');
    }
}
