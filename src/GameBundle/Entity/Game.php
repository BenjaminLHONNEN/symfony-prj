<?php

namespace GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="GameBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2500)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="simple_array")
     */
    private $tags;

    /**
    * @var ratings
    *
    * @ORM\OneToMany(targetEntity="Rating", mappedBy="game")
    */
    private $ratings;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Game
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Game
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return Game
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
    * Set ratingIds
    *
    * @param array $ratings
    *
    * @return Rating
    */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
        return $this;
    }


    /**
    * Get ratings
    *
    * @return array
    */
    public function getRatings()
    {
        return $this->ratings;
    }

    public function getAverageRating()
    {
        $sumOfVote = 0;
        $numberOfVote = 0;
        foreach ($this->ratings as $rating){
            $numberOfVote++;
            $sumOfVote += $rating->getNote();
        }
        if ($numberOfVote === 0){
            $numberOfVote = 1;
        }
        return $sumOfVote/$numberOfVote;
    }

    public function __toString()
    {
        return "Game Id  : " . $this->getId();
    }
}

