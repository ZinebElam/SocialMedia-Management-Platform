<?php
  namespace App\Controller;

  use App\Entity\User;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;

  class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('home.html.twig',[
            'current_menu' => 'home'
        ]);
    }
  }
