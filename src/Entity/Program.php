<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;

//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”

use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity('title')]
class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
    )]

    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]

    private ?string $synopsis = null;

   // #[ORM\Column(length: 255, nullable: true)]
    //private ?string $poster = null;
// On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne

    // Tu peux d’ailleurs voir que l’attribut ORM column n’est pas spécifié car

    // On ne rajoute pas de données de type file en bdd


    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;


    #[ORM\ManyToMany(targetEntity: Actor::class, mappedBy: 'programs')]
    private Collection $actors;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $poster = null;
// On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne

    // Tu peux d’ailleurs voir que l’attribut ORM column n’est pas spécifié car

    // On ne rajoute pas de données de type file en bdd

    //#[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
    //private ?File $posterFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;
    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }



    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeProgram($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }
    public function setPosterFile(File $image = null): Program

    {
        if ($image) {

            $this->updatedAt = new \DateTime('now');

        }
        $this->posterFile = $image;

        return $this;

    }


    public function getPosterFile(): ?File

    {

        return $this->posterFile;

    }

    public function getDatetimeInterface(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setDatetimeInterface(?\DateTimeInterface $updatedAt): self
    {
        $this->DatetimeInterface = $updatedAt;

        return $this;
    }



}
