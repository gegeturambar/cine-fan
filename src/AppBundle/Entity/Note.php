<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Note
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 * @UniqueEntity(
 *     fields= {"user", "movie"},
 *     message="You have already give a note to this movie"
 * )
 *
 */
class Note
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
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

	/**
	 * @var
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie")
	 * @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
	 * @Assert\NotBlank(message="Le film doit être renseigné")
	 */
	private $movie;

	/**
	 * @var
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @Assert\NotBlank(message="L'utilisateur doit être renseigné")
	 */
	private $user;

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
     * Set value
     *
     * @param integer $value
     *
     * @return Note
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

	/**
	 * @return Movie
	 */
	public function getMovie()
	{
		return $this->movie;
	}

	/**
	 * @param mixed $movie
	 */
	public function setMovie($movie)
	{
		$this->movie = $movie;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}

