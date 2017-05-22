<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentaireRepository")
 * @ORM\EntityListeners({"AppBundle\Service\Listener\CommentaireListener"})
 */
class Commentaire
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
	 * @ORM\Column(name="commentaire", type="text")
	 */
	private $commentaire;

	/**
	 * @return string
	 */
	public function getCommentaire()
	{
		return $this->commentaire;
	}

	/**
	 * @param string $commentaire
	 */
	public function setCommentaire($commentaire)
	{
		$this->commentaire = $commentaire;
	}

	/**
	 * @return mixed
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
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", length=255, unique=true)
	 */
	private $slug;

	/**
	 * @return mixed
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	/**
	 * @ORM\Column(name="published", type="boolean")
	 */
	private $published = true;

	/**
	 * @return mixed
	 */
	public function getPublished()
	{
		return $this->published;
	}

	/**
	 * @param mixed $published
	 */
	public function setPublished($published)
	{
		$this->published = $published;
	}

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
}

