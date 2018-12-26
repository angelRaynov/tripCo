<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\CarType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/car/create", name="car_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCar(Request $request)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);

        $countMsg = $this->messageCount();

        if ($form->isSubmitted()) {
            /** @var UploadedFile $file */
            $file = $form->getData()->getPicture();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('car_directory'), $fileName);
            } catch (FileException $ex) {

            }
            $car->setPicture($fileName);
            $car->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();
            return $this->redirectToRoute('user_cars');
        }
        return $this->render('car/create.html.twig',
            array('car' => $car, 'countMsg' => $countMsg));

    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/car/edit/{id}", name="car_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editCar($id, Request $request)
    {
        $car = $this->getDoctrine()->getRepository(Car::class)->find($id);
        $countMsg = $this->messageCount();
//
        if ($car === null) {
            $this->redirectToRoute('default_index');
        }
//
        /** @var User $user */
        $user = $this->getUser();
        if (!$user->isOwner($car) && !$user->isAdmin()) {
            return $this->redirectToRoute('default_index');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
//
        if ($form->isSubmitted()) {

            /** @var UploadedFile $file */
            $file = $form->getData()->getPicture();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('car_directory'), $fileName);
            } catch (FileException $ex) {

            }
            $car->setPicture($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            return $this->redirectToRoute('car_view', array(
                'id' => $car->getId(),
            ));
        }
//
//
        return $this->render('car/edit.html.twig',
            array('car' => $car,
                'countMsg' => $countMsg));
    }

    /**
     * @Route("/car/delete/{id}", name="car_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCar(Request $request, $id)
    {
        $car = $this->getDoctrine()->getRepository(Car::class)->find($id);

        if ($car === null) {
            $this->redirectToRoute('default_index');
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $countMsg = $this->messageCount();
        if (!$currentUser->isOwner($car) && !$currentUser->isAdmin()) {
            return $this->redirectToRoute('default_index');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($car);
            $em->flush();
            return $this->redirectToRoute('user_cars');
        }


        return $this->render('car/delete.html.twig',
            array('car' => $car, 'form' => $form->createView(), 'countMsg' => $countMsg)
        );
    }

    /**
     * @Route("/car/{id}", name="car_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewCar($id)
    {
        $car = $this->getDoctrine()->getRepository(Car::class)->find($id);
        $countMsg = $this->messageCount();
        return $this->render('car/car.html.twig', ['car' => $car, 'countMsg' => $countMsg]);
    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myCars", name="user_cars")
     */
    public function myCars()
    {
        $cars = $this->getDoctrine()->getRepository(Car::class)
            ->findBy(['owner' => $this->getUser()]);

        $countMsg = $this->messageCount();

        return $this->render('user/myCars.html.twig',
            ['cars' => $cars, 'countMsg' => $countMsg]
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
        return count($unreadMessages);
    }
}

