<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Movie
 *
 * @ORM\Table(name="movie", uniqueConstraints={ @ORM\UniqueConstraint(columns={"title", "release_date"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 * @ORM\EntityListeners({"AppBundle\Service\Listener\MovieListener"})
 * @UniqueEntity(
 *     fields= {"releaseDate", "title"},
 *     message = "This movie already exists in our database"
 * )
 *
 */
class Movie
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
     * @ORM\Column(name="title", type="string", length=255)
     * * @Assert\NotBlank(message="Vous devez remplir un titre de film")
     * * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least 2 characters long",
     *      maxMessage = "Your first name cannot be longer than 50 characters"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="release_date", type="date")
     * @Assert\Date(
     *
     * )
     *
     */
    private $releaseDate;

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
	 * @ORM\Column(name="published", type="boolean")
	 */
	private $published = false;

	/**
	 * @return mixed
	 */
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * @param mixed $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * @ORM\Column(name="price", type="float")
	 */
	private $price = 0;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * * @Assert\NotBlank(message="La categorie doit être renseignée")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", mappedBy="movies")
     */
    private $actors;

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaire", mappedBy="movie")
	 */
	private $commentaires;

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

	/**
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="movie")
	 */
	private $notes;

	/**
	 * @return mixed
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes($notes)
	{
		$this->notes = $notes;
	}

	public function getGlobalNote(){
		if(!count($this->notes))
			return null;

		$concatNote = 0;

		/**
		 * @var Note $note
		 */
		foreach ($this->notes as $note){
			$concatNote += $note->getValue();
		}
		return $concatNote / count($this->notes);

	}


	/**
	 * @return mixed
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @param mixed $tags
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
	}

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="movies")
	 * @ORM\JoinTable(name="tags_movies")
	 */
	private $tags;


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
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Movie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Movie
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
     * Set picture
     *
     * @param string $picture
     *
     * @return Movie
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     * @return Movie
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Movie
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }




    public function __construct()
    {
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actor
     *
     * @param \AppBundle\Entity\Actor $actor
     *
     * @return Movie
     */
    public function addActor(\AppBundle\Entity\Actor $actor)
    {
        $this->actors[] = $actor;

        return $this;
    }

    /**
     * Remove actor
     *
     * @param \AppBundle\Entity\Actor $actor
     */
    public function removeActor(\AppBundle\Entity\Actor $actor)
    {
        $this->actors->removeElement($actor);
    }

	/**
	 * Add tag
	 *
	 * @param \AppBundle\Entity\Tag $tag
	 *
	 * @return Movie
	 */
	public function addTag(\AppBundle\Entity\Tag $tag)
	{
		$this->tags[] = $tag;

		return $this;
	}

	/**
	 * Remove tag
	 *
	 * @param \AppBundle\Entity\Tag $tag
	 */
	public function removeTag(\AppBundle\Entity\Tag $tag)
	{
		$this->tags->removeElement($tag);
	}

    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActors()
    {
        return $this->actors;
    }


}

