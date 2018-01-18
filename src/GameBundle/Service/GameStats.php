<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 16/10/2017
 * Time: 15:40
 */

namespace GameBundle\Service;

use Doctrine\ORM\EntityManager;
use GameBundle\Entity\Game;

class GameStats
{
    private $em;
    private $game;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAverageNote(int $gameId){
        $gameRepository = $this->em->getRepository(Game::class);
        $this->game = $gameRepository->find($gameId);
        $sumOfVote = 0;
        $numberOfVote = 0;
        foreach ($this->game->getRatings() as $rating){
            $numberOfVote++;
            $sumOfVote += $rating->getNote();
        }
        if ($numberOfVote === 0){
            $numberOfVote = 1;
        }
        return $sumOfVote/$numberOfVote;
    }
}