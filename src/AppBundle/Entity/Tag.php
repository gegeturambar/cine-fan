<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 * @ORM\EntityListeners({"AppBundle\Service\Listener\TagListener"})
 */
class Tag
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->movies = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Add movie
	 *
	 * @param \AppBundle\Entity\Movie $movie
	 *
	 * @return Actor
	 */
	public function addMovie(\AppBundle\Entity\Movie $movie)
	{
		$this->movies[] = $movie;

		return $this;
	}

	/**
	 * Remove movie
	 *
	 * @param \AppBundle\Entity\Movie $movie
	 */
	public function removeMovie(\AppBundle\Entity\Movie $movie)
	{
		$this->movies->removeElement($movie);
	}

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

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
	private $published = false;

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
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Movie", mappedBy="tags")
	 */
	private $movies;

	/**
	 * @return mixed
	 */
	public function getMovies()
	{
		return $this->movies;
	}

	/**
	 * @param mixed $movies
	 */
	public function setMovies($movies)
	{
		$this->movies = $movies;
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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
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
}

