<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customers_list","customer_show"})
     * @Serializer\Expose()
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private $country;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({"customers_list","customer_show"})
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"customer_show"})
     * @Serializer\Expose()
     */
    private ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
