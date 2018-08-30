<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */

class User implements UserInterface {
    
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    /**
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;   
    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
   
    
    public function getId () {
        return $this->id;        
    }
    public function getUsername () {
        return $this->username;        
    }    
    public function getPassword () {
        return $this->password;        
    }  
    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    public function eraseCredentials() {
        
    }
    public function getRoles() {
        return ['ROLE_USER'];
    }
    
    public function getSalt() {
        
    }

}
