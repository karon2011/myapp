<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{

    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/auth", name="auth")
     */
    public function index()
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/api/register", name="security_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        // $roles = $request->request->get('roles');
        // if (!$roles) {
        //     $roles = json_encode([]);
        // }

        $user = new User(); 
        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);

            $user->setUsername($parametersAsArray['username']);
            $user->setEmail($parametersAsArray['email']);
            $user->setPassword($encoder->encodePassword($user, $parametersAsArray['password']));

            // if ($parametersAsArray['roles']) {
            //     $roles = json_encode($parametersAsArray['roles']);
            //     $user->setRoles($roles);
            // }
            
            $em->persist($user);
            $em->flush();
        }

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    /**
     * @Route("/api/login_check", name="login", methods={"POST"})
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = $this->getUser();

        // return $this->json([
        //     'username' => $user->getUsername(),
        //     'roles' => $user->getRoles(),
        // ]);

        // return new Response(sprintf('User %s successfully created', $user->getUsername()));
        // return new Response(sprintf('public function login'));
        return new JsonResponse($user,200);
    }
}
