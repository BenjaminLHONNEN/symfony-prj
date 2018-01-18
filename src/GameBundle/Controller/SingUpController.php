<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 12/10/2017
 * Time: 11:58
 */

namespace GameBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use GameBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SingUpController extends Controller
{
    /**
     * @Route("/user/sign/up", name="signUp")
     */
    public function signAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $passEncoder = $this->get("security.password_encoder");

        $form = $this->createFormBuilder($user)
            ->add('pseudo', TextType::class, array('label' => 'Pseudo : '))
            ->add('mail', EmailType::class, array('label' => 'Mail : '))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => array('label' => 'Password : '),
                'second_options' => array('label' => 'Repeat Password : '),
            ))
            ->add('imageLink', FileType::class, array(
                'label' => 'Image (png,gif,jpg) : ',
                "required" => false,
            ))
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Sign Up',
                    "attr" => array("class" => "button-6-1"),
                ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getImageLink();

            $user->setPassword($passEncoder->encodePassword($user, $user->getPassword()));
            $user->setRole("ROLE_USER");

            if ($file) {
                $fileName = "./asset/userImages/" . md5($user->getMail()) . ".gif";
                $user->setImageLink($fileName);
            } else {
                $fileName = "./asset/userImages/1.gif";
                $user->setImageLink($fileName);
            }

            $em->persist($user);
            $em->flush();

            if($file) {
                $file->move(
                    $this->getParameter('user_image_directory'),
                    $fileName
                );
            }

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute($request->request->get("route") ?? "game_list");
        }

        return $this->render('GameBundle:Game:signUp.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}