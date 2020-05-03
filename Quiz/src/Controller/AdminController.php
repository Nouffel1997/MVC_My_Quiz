<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EntityUserType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use octrine\Common\Annotations\DocLexer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/users", name="users")
     */

    public function users(UserRepository $user)
    {
        return $this->render('admin/users.html.twig', [
            'users' => $user->findAll()
        ]);
    }



    /**
     * @Route("/users/edit/{id}", name="edit_user")
     */

    public function UserEdit(Request $request, User $user, EntityManagerInterface $em)
    {
        $form = $this->createForm(EntityUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/editUser.html.twig', ['formUser' => $form->createView()]);
    }




    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/users/delete/{id}",name="delete_user")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        return $this->redirectToRoute('admin_users');
    }

    /** 
     * @Route("/users/new", name="new_use")
     * Method({"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
       
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $token = bin2hex(random_bytes(10));
            $user->setToken($token);
            $hashToken = $encoder->encodePassword($user, $user->getToken());
            $user->setToken($hashToken);

            $em->persist($user);
            $em->flush();

            

            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/createUser.html.twig', ['formUser' => $form->createView()]);
    }




































    // public function delete(Request $request, User $user, EntityManagerInterface $em)
    // {


    //     // $form = $this->createForm(EntityUserType::class, $user);
    //     // $form->handleRequest($request);
    //     // if ($form->isSubmitted() && $form->isValid()) {
    //     //     $em->remove($form);
    //     //     $em->flush();

    //     $Categorie = $this->getDoctrine()->getRepository(EntityUserType::class)->find($user);

    //     $em = $this->getDoctrine()->getManager();
    //     $em->remove($Categorie);
    //     $em->flush();

    //     return $this->redirectToRoute('admin_users');
    //     }


}
