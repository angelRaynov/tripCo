<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car;
use AppBundle\Entity\Message;
use AppBundle\Entity\Offer;
use AppBundle\Entity\User;
use AppBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/offer/create", name="offer_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createOffer(Request $request)
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        $countMsg = $this->messageCount();

        if ($form->isSubmitted()) {
            $offer->setDriver($this->getUser());
            $offer->setCar($offer->getCar()->getCarName());         // if it's stupid but it works, it ain't stupid :D
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            return $this->redirectToRoute('default_index');
        }
        return $this->render('offer/create.html.twig',
            array('form' => $form->createView(), 'countMsg' => $countMsg));
    }


    /**
     * @Route("/offer/{id}", name="offer_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewOffer($id)
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($id);
        $countMsg = $this->messageCount();
        return $this->render('offer/offer.html.twig', ['offer' => $offer, 'countMsg' => $countMsg]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/offer/edit/{id}", name="offer_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editOffer($id, Request $request)
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($id);
        $countMsg = $this->messageCount();

        if ($offer === null) {
            $this->redirectToRoute('default_index');
        }

        /** @var User $currentUser*/
        $currentUser = $this->getUser();
        if(!$currentUser->isDriver($offer) && !$currentUser->isAdmin()){
          return $this->redirectToRoute('default_index');
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setCar($offer->getCar()->getCarName());
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            return $this->redirectToRoute('offer_view', array(
                'id' => $offer->getId(),
            ));
        }


        return $this->render('offer/edit.html.twig',
            array('offer' => $offer,
                'form' => $form->createView(),
                'countMsg' => $countMsg));
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myOffers", name="user_offers")
     *
     *
     */
    public function myOffers()
    {
        $offers = $this->getDoctrine()->getRepository(Offer::class)
            ->findBy(['driverId' => $this->getUser()]);

        $countMsg = $this->messageCount();

        return $this->render('user/myOffers.html.twig',
            ['offers' => $offers, 'countMsg' => $countMsg]
        );
    }

    /**
     * @Route("/offer/delete/{id}", name="offer_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteOffer(Request $request, $id)
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($id);
        $countMsg = $this->messageCount();
        if ($offer === null) {
            $this->redirectToRoute('default_index');
        }

        /** @var User $currentUser*/
        $currentUser = $this->getUser();
        if(!$currentUser->isDriver($offer) && !$currentUser->isAdmin()){
            return $this->redirectToRoute('default_index');
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offer);
            $em->flush();
            return $this->redirectToRoute('user_offers');
        }

        return $this->render('offer/delete.html.twig',
            array('offer' => $offer, 'form' => $form->createView(), 'countMsg' => $countMsg)
        );
    }

   public function messageCount()
    {
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        $unreadMessages = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->findBy(['recipient' => $user, 'isReaded' => false]);
       return $countMsg = count($unreadMessages);
    }
}


