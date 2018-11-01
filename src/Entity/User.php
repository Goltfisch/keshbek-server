<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @var int
     * 
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $email;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $paypalMeLink;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $avatarLink;

    /**
     * @var DateTime
     * 
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set paypal me link
     *
     * @param string $paypalMeLink
     *
     * @return User
     */
    public function setPayPalMeLink($paypalMeLink)
    {
        $this->paypalMeLink = $paypalMeLink;

        return $this;
    }

    /**
     * Get paypal me link
     *
     * @return string
     */
    public function getPayPalMeLink()
    {
        return $this->paypalMeLink;
    }

    /**
     * Set avatar link
     *
     * @param string $avatarLink
     *
     * @return User
     */
    public function setAvatarLink($avatarLink)
    {
        $this->avatarLink = $avatarLink;

        return $this;
    }

    /**
     * Get avatar link
     *
     * @return string
     */
    public function getAvatarLink()
    {
        return $this->avatarLink;
    }

    /**
     * Get created at Date
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the salt
     * 
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get the roles of the user
     * 
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Erase the credentils
     * 
     * @return void
     */
    public function eraseCredentials()
    {
        
    }
}