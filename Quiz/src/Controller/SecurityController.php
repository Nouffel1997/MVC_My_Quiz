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
use src\Repository\QuestionRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
    /*public function categorieQuestion($categories)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByCategories($categories);
        $question = $this->getDoctrine()->getRepository('App:Question')->findByCategorie($categories);
        return $this->render('question.html.twig', array('question' => $question));
    }*/
    public function question()
    {
        $posts = $this->getDoctrine()->getRepository('App:Question')->findBy(array('id' => 1));

        dump($posts);

        return $this->render(
            'security/question.html.twig',
            [
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
            'security/categorie.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    /**
     * @Route("/reponse", name="security_reponse")
     */

    public function reponse()
    {
        $posts = $this->getDoctrine()->getRepository('App:Reponse')->findAll();

        dump($posts);

        return $this->render(
            'security/reponse.html.twig',
            [
                'posts' => $posts
            ]
        );
    }









    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ) {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('security_login');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('security_login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "blablabla voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/forgotten_password.html.twig');
    }
}
