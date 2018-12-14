<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default_index")
     */
    public function indexAction()
    {
//        $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();
////        dump($articles);exit();
//        return $this->render('default/index.html.twig', ['articles' => $articles]);
        return $this->render('default/index.html.twig');
    }
}
