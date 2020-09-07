<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * @UniqueEntity(
 *     fields={"fullName"},
 *     message="Ce nom de client est déjà utilisé.",
 *     groups={"Create"}
 * )
 * @Hateoas\Relation(
 *     "self",
 *     href= @Hateoas\Route(
 *     "app_customer_show",
 *     parameters = {"id" ="expr(object.getId())"},
 *     absolute = true
 *     ),
 *     exclusion=@Hateoas\Exclusion(groups = {"customers_list"})
 * )
 * @Hateoas\Relation(
 *     "list",
 *     href= @Hateoas\Route(
 *     "app_list_customers",
 *     absolute = true
 *     ),
 *     exclusion=@Hateoas\Exclusion(groups = {"customers_show"})
 * )
 * @Hateoas\Relation(
 *     "delete",
 *     href= @Hateoas\Route(
 *     "app_customer_delete",
 *     parameters = {"id" ="expr(object.getId())"},
 *     absolute = true
 *     ),
 *     exclusion=@Hateoas\Exclusion(groups = {"customers_list","customer_show"})
 * )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"customers_list","after_creation","customer_show"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customers_list","customer_show","after_creation","Create"})
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Votre nom ne peux pas être vide", groups={"Create"})
     * @Assert\Length(min="5",
     *      max="255",
     *      minMessage="Votre nom est trop court (5 caractères minimum)",
     *      maxMessage="Votre nom est trop long (255 caractères max).",
     *      groups={"Create"}
     * )
     * @Serializer\Since("1.0")
     */
    private ?string $fullName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show","after_creation","Create"})
     * @SWG\Property(description="Must be a valid email format")
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Votre email ne peux pas être vide", groups={"Create"})
     * @Assert\Email(message="Votre email doit avoir un format correct", groups={"Create"})
     * @Serializer\Since("1.0")
     */
    private ?string $email;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"customer_show","after_creation","Create"})
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Votre rue ne peux pas être vide", groups={"Create"})
     * @Assert\Length(min="5",
     *      max="255",
     *      minMessage="Votre nom de rue est trop court (5 caractères minimum)",
     *      maxMessage="Votre nom de rue est trop long (255 caractères max).",
     *      groups={"Create"}
     * )
     * @Serializer\Since("1.0")
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show","after_creation","Create"})
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Votre nom de ville ne peux pas être vide", groups={"Create"})
     * @Assert\Length(min="2",
     *      max="255",
     *      minMessage="Votre nom de ville est trop court (2 caractères minimum)",
     *      maxMessage="Votre nom de ville est trop long (255 caractères max).",
     *      groups={"Create"}
     * )
     * @Serializer\Since("1.0")
     */
    private ?string $city;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"customer_show","after_creation","Create"})
     * @Serializer\Expose()
     * @Assert\Regex("#^[0-9]{4,5}#",message="Votre code postal doit contenir entre 4 et 5 chiffres",
     *     groups={"Create"})
     * @Serializer\Since("1.0")
     */
    private ?int $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"customer_show","after_creation","Create"})
     * @Serializer\Expose()
     * @Assert\NotBlank(message="Votre Pays ne peux pas être vide", groups={"Create"})
     * @Assert\Length(min="2",
     *      max="255",
     *      minMessage="Votre nom de pays est trop court (2 caractères minimum)",
     *      maxMessage="Votre nom de pays est trop long (255 caractères max).",
     *      groups={"Create"}
     * )
     * @Serializer\Since("1.0")
     */
    private ?string $country;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({"customers_list","customer_show","after_creation"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     */
    private ?\DateTimeImmutable $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"customer_show","after_creation"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
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
