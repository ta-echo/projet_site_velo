<?php

namespace App\Entity;

use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetailCommandeRepository::class)
 */
class DetailCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="detailCommandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
   
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_unit;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getPrixUnit(): ?float
    {
        return $this->prix_unit;
    }

    public function setPrixUnit(float $prix_unit): self
    {
        $this->prix_unit = $prix_unit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
