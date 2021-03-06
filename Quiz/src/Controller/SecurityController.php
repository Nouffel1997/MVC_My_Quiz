<?php

namespace App\Controller;


use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Form\RegistrationType;
//use Symfony\Component\BrowserKit\Request;
use src\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ReponseRepository as RepositoryReponseRepository;
use App\Repository\QuestionRepository as QquestionRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\CategorieRepository as RepositoryCategorieRepository;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder,  \Swift_Mailer $mailer)
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

            $emailUser = $user->getEmail();


            $url = $this->generateUrl('token', ['token' => $token, 'id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Email de Confirmation'))
                ->setSubject('Confirmation d\'adresse email')
                ->setFrom('nouf.2nd2@gmail.com')
                ->setTo($emailUser)
                ->setBody(
                    $this->renderView('security/contenue.html.twig', [
                        'user' => $user,
                        'url' => $url,
                        'token' => $token
                    ]),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('message', 'Un mail de confirmation vous a été envoyé');

            return $this->redirectToRoute('confirm_mail');
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    // public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    // {
    //     $user = new User();

    //     $form = $this->createForm(RegistrationType::class, $user);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $hash = $encoder->encodePassword($user, $user->getPassword());
    //         $user->setPassword($hash);

    //         $em->persist($user);
    //         $em->flush();
    //     }
    //     return $this->render(
    //         'security/registration.html.twig',
    //         ['form' => $form->createView()]
    //     );
    // }


  /**
     * @Route("/confirm_mail", name="confirm_mail")
     */

    public function confirm_mail()
    {

        $user =  $this->getDoctrine()
            ->getRepository(User::class);

        return $this->render('security/mailconf.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/confirm_token/{token}", name="token")
     */
    public function confirm_token(string $token, AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/token.html.twig',
            [
                'token' => $token,
                'lastusername' => $lastUsername
            ]
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


    public function categorieQuestione(QquestionRepository $categories)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByQuestion();
        $question = $this->getDoctrine()->getRepository('App:Question')->findAll();
        return $this->render('question.html.twig', array('question' => $question));
    }

    /**
     * @Route("/question", name="security_questiones")
     */
    public function question()
    {
        $posts = $this->getDoctrine()->getRepository('App:Question')->findBy(['id_categorie' => 1, 'id' => 1]);
        
        $bob = $this->getDoctrine()->getRepository('App:Reponse')->findBy(['id_question' => 1]);
        
        dump($posts);
        dump($bob);

        return $this->render(
            'security/question.html.twig',
            [
                'posts' => $posts,
                'bobs' => $bob,
                
            ]
        );
    }


     /**
     * @Route("/question", name="security_question")
     */
    public function questiones()
    {
        
        $q2 = $this->getDoctrine()->getRepository('App:Question')->findBy(['id_categorie' => 2, 'id' => 2]);
        
        $r2 = $this->getDoctrine()->getRepository('App:Reponse')->findBy(['id_question' => 2]);
       
       

        return $this->render(
            'security/question.html.twig',
            [
                
                'q2' => $q2,
                'r2' => $r2,
                
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
        $posts = $this->getDoctrine()->getRepository('App:Reponse')->findBy(['id_question' => 1, 'reponse_expected' => 1]);

        dump($posts);

        return $this->render(
            'security/reponse.html.twig',
            [
                'posts' => $posts
            ]
        );
    }


     /**
     * @Route("/sigle", name="sigle")
     */

    public function categorieSigle(QquestionRepository $question, RepositoryReponseRepository $moi, Request $request)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByCategories($categories);
        //$question = $question->findByQuestion(3, 1);
        //$moi = $this->getDoctrine()->getManager()->getRepository('App:Reponse')->findByReponse(1, 3);
        
        return $this->render('security/sigle.html.twig', ['posts' => $question->findByQuestion((int)$request->query->get('page', 11), 1), 'bob' => $moi->findByReponse((int)$request->query->get('page', 11), 3),
        'totalPosts' => $question->count()
        ]);
    }

    /**
     * @Route("/bob", name="bob")
     */

    public function categorieQuestion(QquestionRepository $question, RepositoryReponseRepository $moi, Request $request)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByCategories($categories);
        //$question = $question->findByQuestion(3, 1);
        //$moi = $this->getDoctrine()->getManager()->getRepository('App:Reponse')->findByReponse(1, 3);
        return $this->render('security/question.html.twig', ['posts' => $question->findByQuestion((int)$request->query->get('page', 1), 1), 'bob' => $moi->findByReponse((int)$request->query->get('page', 1), 3),
        'totalPosts' => $question->count()
        ]);
    }

    /**
     * @Route("/definition", name="definition")
     */

    public function categorieDefinition(QquestionRepository $question, RepositoryReponseRepository $moi, Request $request)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByCategories($categories);
        //$question = $question->findByQuestion(3, 1);
        //$moi = $this->getDoctrine()->getManager()->getRepository('App:Reponse')->findByReponse(1, 3);
        
        return $this->render('security/definition.html.twig', ['posts' => $question->findByQuestion((int)$request->query->get('page', 11), 1), 'bob' => $moi->findByReponse((int)$request->query->get('page', 11), 3),
        'totalPosts' => $question->count()
        ]);
    }



 /**
     * @Route("/reponseexpected", name="reponse_expected")
     */

    public function reponseExpected(QquestionRepository $question, RepositoryReponseRepository $moi, Request $request)
    {
        //$em = $this->getDoctrine()->getManager();
        //$question = $em->getRepository('App:Question')->findByCategories($categories);
        //$question = $question->findByQuestion(1, 1);
        //$moi = $this->getDoctrine()->getManager()->getRepository('App:Reponse')->findByReponseExpected(3, 1);
        return $this->render('security/reponse.html.twig', array('posts' => $moi->findByReponseExpected((int)$request->query->get('page', 1), 1)));
    }


    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    /*public function forgottenPassword(
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

            /*if ($user === null) {
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
    }*/

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/categorie/new", name="new_categorie")
     * Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $Categorie = new Categorie();
        $form = $this->createFormBuilder($Categorie)
            ->add('name', TextType::class)
            ->add('save',SubmitType::class,array('label' => 'Créer'))->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Categorie = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Categorie);
            $entityManager->flush();

            return $this->redirectToRoute('security_categorie');
        }
        return $this->render('security/createcat.html.twig', ['form' => $form->createView()]);
    }

    /**
     *  @IsGranted("ROLE_ADMIN")
     * @Route("/categorie/edit/{id}", name="edit_categorie")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $Categorie = new Categorie();
        $Categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);

        $form = $this->createFormBuilder($Categorie)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array(
                'label' => 'Modifier'
            ))->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('security_categorie');
        }

        return $this->render('security/editcat.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
 * @Route("/categorie/delete/{id}",name="delete_categorie")
 * 
 */
 public function delete(Request $request, $id) {
    $Categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
   
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($Categorie);
    $entityManager->flush();
   
    
    
    return $this->redirectToRoute('security_categorie');
    }
}
