<?php

namespace App\Controller;

use App\Controller\AuthController; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $controller;

    public function __construct(AuthController $controller)
    {
        $this->controller = $controller;
    }
    public function __invoke(Request $request, UserPasswordEncoderInterface $encoder)
    {
        return $this->controller->register($request, $encoder);
    }
}
