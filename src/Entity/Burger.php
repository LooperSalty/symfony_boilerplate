<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Burger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private $price;

    #[ORM\ManyToOne(targetEntity: Pain::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $pain;

    #[ORM\ManyToMany(targetEntity: Oignon::class)]
    private $oignons;

    #[ORM\ManyToMany(targetEntity: Sauce::class)]
    private $sauces;

    #[ORM\OneToOne(targetEntity: Image::class)]
    private $image;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'burger')]
    private $commentaires;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Burger = null;

    public function __construct()
    {
        $this->oignons = new ArrayCollection();
        $this->sauces = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getPain(): ?Pain
    {
        return $this->pain;
    }

    public function setPain(?Pain $pain): self
    {
        $this->pain = $pain;
        return $this;
    }

    public function getOignons(): Collection
    {
        return $this->oignons;
    }

    public function addOignon(Oignon $oignon): self
    {
        if (!$this->oignons->contains($oignon)) {
            $this->oignons->add($oignon);
        }
        return $this;
    }

    public function removeOignon(Oignon $oignon): self
    {
        $this->oignons->removeElement($oignon);
        return $this;
    }

    public function getSauces(): Collection
    {
        return $this->sauces;
    }

    public function addSauce(Sauce $sauce): self
    {
        if (!$this->sauces->contains($sauce)) {
            $this->sauces->add($sauce);
        }
        return $this;
    }

    public function removeSauce(Sauce $sauce): self
    {
        $this->sauces->removeElement($sauce);
        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setBurger($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getBurger() === $this) {
                $commentaire->setBurger(null);
            }
        }
        return $this;
    }

    public function getBurger(): ?string
    {
        return $this->Burger;
    }

    public function setBurger(?string $Burger): static
    {
        $this->Burger = $Burger;

        return $this;
    }
}
