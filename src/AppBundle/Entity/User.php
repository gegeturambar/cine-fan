<?php

// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"username"},message="This username is already used")
 * @UniqueEntity(fields={"email"},message="This mail is already used")
 *
 */
class User implements UserInterface, \Serializable
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=60, unique=true)
	 * @Assert\Email()
	 *
	 */
	private $email;

	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;

	/**
	 * @ORM\ManyToOne(targetEntity="Role")
	 * @ORM\JoinColumn(name="roles", referencedColumnName="id")
	 */
	private $roles;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaire", mappedBy="user")
	 */
	private $commentaires;


	/**
	 * @ORM\Column(name="last_connection", type="datetime", nullable=true)
	 */
	private $lastConnection;

	public function __construct()
	{
		$this->isActive = true;
		$this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
		// may not be needed, see section on salt below
		// $this->salt = md5(uniqid(null, true));
	}

	/**
	 * @return mixed
	 */
	public function getCommentaires()
	{
		return $this->commentaires;
	}

	/**
	 * @param mixed $commentaires
	 */
	public function setCommentaires($commentaires)
	{
		$this->commentaires = $commentaires;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getSalt()
	{
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getRoles()
	{
		return array($this->roles->getName());
	}

	public function eraseCredentials()
	{
	}

	/** @see \Serializable::serialize() */
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt,
		));
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
			// see section on salt below
			// $this->salt
			) = unserialize($serialized);
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
	 * Set isActive
	 *
	 * @param boolean $isActive
	 *
	 * @return User
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get isActive
	 *
	 * @return boolean
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * Set roles
	 *
	 * @param \AppBundle\Entity\Role $roles
	 *
	 * @return User
	 */
	public function setRoles(\AppBundle\Entity\Role $roles = null)
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * Set lastConnection
	 *
	 * @param \DateTime $lastConnection
	 *
	 * @return User
	 */
	public function setLastConnection($lastConnection)
	{
		$this->lastConnection = $lastConnection;

		return $this;
	}

	/**
	 * Get lastConnection
	 *
	 * @return \DateTime
	 */
	public function getLastConnection()
	{
		return $this->lastConnection;
	}
}
