<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 13/10/2017
 * Time: 11:37
 */

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;

class RatingController extends Controller
{
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    /**
     * @Route("/api/ratingsByGameId/{gameId}",requirements={"gameId": "[0-9]+"})
     */
    public function ratingByGameIdAction(int $gameId)
    {
        $time_start = $this->microtime_float();

        $query = $this->getDoctrine()
            ->getRepository('GameBundle:Rating')
            ->createQueryBuilder('r')
            ->where('r.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        $response = new JsonResponse([
            "Ratings" => $result,
            "responseType" => "array",
            "responseLen" => count($result),
            "executionTime" => $time,
        ]);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }
}