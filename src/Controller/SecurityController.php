<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // analyse de la requete
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Encoder le mot de passe on recupere la donnée par le getter
            $password = $encoder->encodePassword($user,$user->getPassword());
            // Une fois la donnée changée, on set celle ci avant envoie ds la bdd
            $user->setPassword($password);

            // traitement des données reçues du form
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->redirectToRoute("security_login");
        }
        return $this->render('security/index.html.twig', [
            'controller_name' => 'Inscription',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        // la redirection est configurée ds le security.yaml
    }
}
