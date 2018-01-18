<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 13/10/2017
 * Time: 11:36
 */

namespace ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

class UserController extends Controller
{
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    /**
     * @Route("/api/user/{userId}",requirements={"userId": "[0-9]+"})
     */
    public function userbyIdAction(int $userId)
    {
        $time_start = $this->microtime_float();

        $userRepository = $this->getDoctrine()->getRepository("GameBundle\Entity\User");
        $user = $userRepository->find($userId);

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        $response = new JsonResponse([
            "User" => [
                "id" => $user->getId(),
                "pseudo" => $user->getPseudo(),
                "image" => "$_SERVER[HTTP_HOST]/" . $user->getImageLink(),
            ],
            "responseType" => "array",
            "responseLen" => 1,
            "executionTime" => $time,
        ]);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }
}