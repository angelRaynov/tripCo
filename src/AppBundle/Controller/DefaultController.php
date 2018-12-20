<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Message;
use AppBundle\Entity\Offer;
use AppBundle\Entity\User;
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
        if ($this->getUser() !== null) {

            $userId = $this->getUser()->getId();
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

            $unreadMessages = $this
                ->getDoctrine()
                ->getRepository(Message::class)
                ->findBy(['recipient' => $user, 'isReaded' => false]);
            $countMsg = count($unreadMessages);

            return $this->render('default/index.html.twig', ['offers' => $offers, 'countMsg' => $countMsg]);

        }
        return $this->render('default/index.html.twig', ['offers' => $offers]);
    }
}
