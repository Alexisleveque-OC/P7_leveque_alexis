<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups("List")
     * @Serializer\Expose()
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups("List")
     * @Serializer\Expose()
     */
    private ?string $price;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Expose()
     */
    private array $characteristic = [];

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class)
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups("List")
     * @Serializer\Expose()
     */
    private ?Brand $brand;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCharacteristic(): ?array
    {
        return $this->characteristic;
    }

    public function setCharacteristic(?array $characteristic): self
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
