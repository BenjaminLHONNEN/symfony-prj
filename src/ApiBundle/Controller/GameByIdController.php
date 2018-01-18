<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 17/10/2017
 * Time: 17:54
 */

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;


class GameByIdController extends Controller
{
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * @Route("/api/gameById/{gameId}",requirements={"gameId": "[0-9]+"})
     */
    public function oneGameAction(int $gameId)
    {
        $time_start = $this->microtime_float();

        $query = $this->getDoctrine()
            ->getRepository('GameBundle:Game')
            ->createQueryBuilder('c')
            ->where('c.id = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;


        $response = new JsonResponse([
            "Games" => $result,
            "responseType" => "array",
            "responseLen" => count($result),
            "executionTime" => $time,
        ]);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }

}