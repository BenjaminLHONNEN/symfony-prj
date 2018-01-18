<?php
/**
 * Created by PhpStorm.
 * User: Ulric
 * Date: 28/09/2017
 * Time: 12:36
 */

namespace GameBundle\Controller;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use GameBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SignInController extends Controller
{
    /**
     * @Route("/user/sign/in", name="login")
     */
    public function loginAction(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('GameBundle:Game:signIn.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}