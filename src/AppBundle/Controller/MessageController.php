<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @Route("/user/{id}/message", name="user_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addMessageAction(Request $request, $id)
    {
        $currentUser = $this->getUser();
        $recipient = $this->getDoctrine()->getRepository(User::class)->find($id);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        $countMsg = count($user->getRecipientMessages());

        if ($form->isSubmitted()) {
            $message
                ->setSender($currentUser)
                ->setRecipient($recipient)
                ->setIsReaded(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message sent!");

            return $this->redirectToRoute("user_message", ['id' => $id, 'countMsg' => $countMsg]);
        }

        return $this->render('user/send_message.html.twig', ['form' => $form->createView(), 'countMsg' => $countMsg]);
    }

    /**
     * @Route("/user/mailbox", name="user_mailbox")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailbox()
    {
        $currentUserId = $this->getUser()->getId();

        $user = $this->getDoctrine()->getRepository(User::class)->find($currentUserId);

        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $countMsg = count($user->getRecipientMessages());

        $messages = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->findBy(['recipient' => $user]);

            return $this->render("user/mailbox.html.twig",
                ["messages" => $messages, 'countMsg' =>$countMsg]);
    }

    /**
     * @Route("/user/mailbox/message/{id}", name="current_message")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageAction(Request $request,$id)
    {
        $message = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->find($id);
        $message->setIsReaded(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $sendMessage = new Message();
        $form = $this->createForm(MessageType::class,$sendMessage);
        $form->handleRequest($request);

        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $countMsg = count($user->getRecipientMessages());

        if ($form->isSubmitted()){
            $sendMessage
                ->setSender($this->getUser())
                ->setRecipient($message->getSender())
                ->setIsReaded(false);


            $em->persist($sendMessage);
            $em->flush();

            $this->addFlash("message", "Message sent!");

            return $this->redirectToRoute("current_message", ['id' => $id, 'countMsg' => $countMsg]);
        }

        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $countMsg = count($user->getRecipientMessages());

        return $this->render("user/message.html.twig",
            ["message" => $message, 'countMsg' => $countMsg]);
    }

}
