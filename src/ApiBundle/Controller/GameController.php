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
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

class GameController extends Controller
{
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }


    /**
     * @Route("/api/games")
     */
    public function allGamesAction()
    {
        $time_start = $this->microtime_float();

        $query = $this->getDoctrine()
            ->getRepository('GameBundle:Game')
            ->createQueryBuilder('c')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);

        $responseArray = [];
        foreach ($result as $game) {
            $query = $this->getDoctrine()
                ->getRepository('GameBundle:Rating')
                ->createQueryBuilder('r')
                ->where('r.game = :gameId')
                ->setParameter('gameId', $game['id'])
                ->getQuery();
            $resultRatings = $query->getResult(Query::HYDRATE_ARRAY);
            $numberOfVote = 0;
            $totalVote = 0;
            foreach ($resultRatings as $rating) {
                $totalVote += $rating["note"];
                $numberOfVote++;
            }
            if ($numberOfVote === 0) {
                $numberOfVote = 1;
            }

            $game['rating'] = $totalVote / $numberOfVote;
            $responseArray[] = $game;
        }

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;

        $response = new JsonResponse([
            "Games" => $responseArray,
            "responseType" => "array",
            "responseLen" => count($result),
            "executionTime" => $time,
        ]);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }

    /**
     * @Route("/api/searchGame/")
     */
    public function searchEmptyGamesAction()
    {
        return $this->allGamesAction();
    }

    /**
     * @Route("/api/searchGame/{entry}")
     */
    public function searchGamesAction($entry)
    {
        $time_start = $this->microtime_float();

        $query = $this->getDoctrine()
            ->getRepository('GameBundle:Game')
            ->createQueryBuilder('c')
            ->getQuery();
        $result = $query->getResult(Query::HYDRATE_ARRAY);
        $searchIn = [];

        foreach ($result as $game) {
            $searchIn[] = [];
            $searchIn[count($searchIn) - 1][] = $game["id"];
            $searchIn[count($searchIn) - 1][] = $game["name"];
            foreach ($game["tags"] as $tag) {
                $searchIn[count($searchIn) - 1][] = $tag;
            }
        }

        $resultResearch = $this->research($searchIn, $entry);
        $responseArray = [];
        foreach ($resultResearch as $resultGame) {
            foreach ($result as $game) {
                if ($game["id"] == $resultGame["researchIn"][0]) {

                    $query = $this->getDoctrine()
                        ->getRepository('GameBundle:Rating')
                        ->createQueryBuilder('r')
                        ->where('r.game = :gameId')
                        ->setParameter('gameId', $game['id'])
                        ->getQuery();
                    $resultRatings = $query->getResult(Query::HYDRATE_ARRAY);
                    $numberOfVote = 0;
                    $totalVote = 0;
                    foreach ($resultRatings as $rating) {
                        $totalVote += $rating["note"];
                        $numberOfVote++;
                    }
                    if ($numberOfVote === 0) {
                        $numberOfVote = 1;
                    }

                    $game['rating'] = $totalVote / $numberOfVote;
                    $game['points'] = $resultGame["point"];
                    $responseArray[] = $game;
                }

            }
        }

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;


        $response = new JsonResponse([
            "Games" => $responseArray,
            "responseType" => "array",
            "responseLen" => count($result),
            "executionTime" => $time,
        ]);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;

    }

    public function research($searchIn, $entry)
    {

        $responseArray = [];
        $entry = strtolower($entry);

        foreach ($searchIn as $row) {
            $responseArray[] = [];
            $point = 0;
            foreach ($row as $sentence) {
                $sentence = strtolower($sentence);
                if ($sentence === $entry) {
                    $point += 10000;
                }
                foreach (explode(" ", $sentence) as $word) {
                    foreach (explode(" ", $entry) as $entryWord) {
                        if ($word === $entryWord) {
                            $point += 100;
                        }
                        foreach (str_split($word) as $letter) {
                            foreach (str_split($entryWord) as $entryLetter) {
                                if ($letter === $entryLetter) {
                                    $point += 1;
                                }
                            }
                        }
                    }
                }
            }
            $responseArray[count($responseArray) - 1]["entry"] = $entry;
            $responseArray[count($responseArray) - 1]["researchIn"] = $row;
            $responseArray[count($responseArray) - 1]["point"] = $point;

        }

        usort($responseArray, array($this, "compareGamesPoints"));
        return $responseArray;
    }

    static function compareGamesPoints($a, $b)
    {
        if ($a["point"] == $b["point"]) {
            return 0;
        }
        return ($a["point"] > $b["point"]) ? -1 : 1;
    }

}