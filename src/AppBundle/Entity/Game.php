<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game", options={"engine":"InnoDB"})
 */
class Game {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="game_name", type="string", length=50)
     */
    private $gameName;

    /**
     * @ORM\Column(name="players_nb", type="integer", length=50)
     */
    private $playersNb;

    /**
     * @ORM\Column(name="status", type="string", length=50,  options={"default" : "waiting"})
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     */
    private $players;

    /**
     * @ORM\Column(name="data", type="text", options={"default" : null})
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gameName
     *
     * @param string $gameName
     *
     * @return Game
     */
    public function setGameName($gameName)
    {
        $this->gameName = $gameName;

        return $this;
    }

    /**
     * Get gameName
     *
     * @return string
     */
    public function getGameName()
    {
        return $this->gameName;
    }

    /**
     * Set playersNb
     *
     * @param integer $playersNb
     *
     * @return Game
     */
    public function setPlayersNb($playersNb)
    {
        $this->playersNb = $playersNb;

        return $this;
    }

    /**
     * Get playersNb
     *
     * @return integer
     */
    public function getPlayersNb()
    {
        return $this->playersNb;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Game
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return Game
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add player
     *
     * @param \AppBundle\Entity\User $player
     *
     * @return Game
     */
    public function addPlayer(\AppBundle\Entity\User $player)
    {
        $this->players[] = $player;

        return $this;
    }

    /**
     * Remove player
     *
     * @param \AppBundle\Entity\User $player
     */
    public function removePlayer(\AppBundle\Entity\User $player)
    {
        $this->players->removeElement($player);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
