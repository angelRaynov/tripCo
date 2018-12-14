<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use AppBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/offer/create", name="offer_create")
//   *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createOffer(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ( $form->isSubmitted()){
            $offer->setDriver($this->getUser());
            $offer->setStartDestination($form->getExtraData()['from']);
            $offer->setEndDestination($form->getExtraData()['to']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            return $this->redirectToRoute('default_index');
        }
        return $this->render('offer/create.html.twig',
            array('form' => $form->createView()));
    }
}
