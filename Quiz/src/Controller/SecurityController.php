<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Question;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */

    public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();
        }
        return $this->render(
            'security/registration.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            ['lastUsername' => $lastUsername, 'error' => $error]
        );
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */

    public function logout()
    {
    }

    /**
     * @Route("/question", name="security_question")
     */

    public function question()
    {
        $posts = $this->getDoctrine()->getRepository('App:Question')->findAll();

        dump($posts);

        return $this->render(
            'security/question.html.twig',[
                'posts' => $posts
            ]
        );
    }

     /**
     * @Route("/categorie", name="security_categorie")
     */

    public function categorie()
    {
        $posts = $this->getDoctrine()->getRepository('App:Categorie')->findAll();

        dump($posts);

        return $this->render(
            'security/categorie.html.twig',[
                'posts' => $posts
            ]
        );
    }


}
