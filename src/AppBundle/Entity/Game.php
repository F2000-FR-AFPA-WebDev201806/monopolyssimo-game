<?php

namespace AppBundle\Entity;

/**
 * Description of Game
 *
 * @author Zensaikeunde
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="game", options={"engine":"InnoDB"})
 */

use Doctrine\ORM\Mapping as ORM;

class Game {
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="game_name", type="string", length=50)
     */       
    protected $game_name;
    
    /**
     * @ORM\Column(name="players_nb", type="string", length=250)
     */       
    
    protected $players_nb;
    
    /**
     * @ORM\Column(name="status", type="string", length=50)
     */
    protected $status;
    

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
        $this->game_name = $gameName;

        return $this;
    }

    /**
     * Get gameName
     *
     * @return string
     */
    public function getGameName()
    {
        return $this->game_name;
    }

    /**
     * Set playersNb
     *
     * @param string $playersNb
     *
     * @return Game
     */
    public function setPlayersNb($playersNb)
    {
        $this->players_nb = $playersNb;

        return $this;
    }

    /**
     * Get playersNb
     *
     * @return string
     */
    public function getPlayersNb()
    {
        return $this->players_nb;
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
}
