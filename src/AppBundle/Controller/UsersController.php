<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Message;
use AppBundle\Entity\Offer;
use AppBundle\Entity\Role;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;


class UsersController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $roleRepository = $this->getDoctrine()->getRepository(Role::class);

            $userRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);


            $user->addRole($userRole);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("security_login");
        }


        return $this->render("user/register.html.twig");
    }

    /**
     * @Route("/profile", name="user_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile()
    {
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        $countMsg = $this->messageCount();


        return $this->render("user/profile.html.twig", ['user' => $user, 'countMsg' => $countMsg]);
    }

    /**
     * @Route("/profile/update", name="user_update")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateProfile(Request $request)
    {
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        $countMsg =$this->messageCount();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user->setAge($form->getData()->getAge());
            $user->setPhone($form->getData()->getPhone());
            $user->setBio($form->getData()->getBio());


            /** @var UploadedFile $file */
            $file = $form->getData()->getAvatar();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('avatar_directory'), $fileName);
            } catch (FileException $ex) {

            }
//            var_dump($fileName);exit();
            $user->setAvatar($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render("user/update.html.twig", ['user' => $user, 'countMsg' => $countMsg]);
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
