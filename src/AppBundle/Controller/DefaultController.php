<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Offer;
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
        $offers = $this->getDoctrine()->getRepository(Offer::class)->findAll();

        return $this->render('default/index.html.twig',['offers' => $offers]);
    }
}
