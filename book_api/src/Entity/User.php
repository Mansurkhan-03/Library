<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Component\User\FullNameDto;
use App\Controller\user\AboutMeAction;
use App\Controller\user\UserChangeAction;
use App\Controller\user\UserChangePasswordAction;
use App\Controller\user\UserCreateAction;
use App\Controller\user\UserFullNameAction;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
        ),

        new GetCollection(
            uriTemplate: 'users/about-me',
            controller: AboutMeAction::class,
            name: 'aboutMe',
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') || object == user",
        ),
        new Delete(),
        new Post(
            uriTemplate: "users/my",
            controller: UserCreateAction::class,
            validate: false,
            name: "createUser",
        ),
        new Post(
            uriTemplate: "users/full_name",
            controller: UserFullNameAction::class,
            input: FullNameDto::class,
            name: "fullName"
        ),
        new Post(
            uriTemplate: "users/auth",
            controller: UserCreateAction::class,
            name: "auth"
        ),
        new Patch(
            uriTemplate: "users/update_password",
            controller: UserChangePasswordAction::class,
            validate: false,
            name: "updatePassword",
            parameters: [
                "email" => new QueryParameter(schema: ['type' => 'string'])
            ]
        ),
        new Patch(
            uriTemplate: "users/update",
            controller: UserChangeAction::class,
            validate: false,
            name: "update",
            parameters: [
                "id" => new QueryParameter(schema: ['type' => 'integer'])
            ]
        )
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    paginationItemsPerPage: 5

)]
#[UniqueEntity(fields: ['email'], message: 'This username already exists: {{ value }}.')]
#[ApiFilter(SearchFilter::class, properties: [
    "id" => "exact",
    "email" => "partial",
    "phone" => "end",
    "gender" => "exact"
])]
#[ApiFilter(OrderFilter::class, properties: [
    "id" => "desc",
    "fullName" => "desc"
])]
#[ApiFilter(DateFilter::class, properties: [
    "createdAt" => "exact"
])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write', 'book:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email bo'sh bo'lishi mumkin emas!")]
    #[Assert\Email(message: "Email formatiga to'g'ri kelishi kerak: a@a.com")]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(min: 18, max: 50)]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(

        choices: ['male', 'female'],
        message: "Bunday jins qabul qilinmaydi."
    )]
    #[Groups(['user:read', 'user:write'])]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 9, max: 13)]
    #[Assert\Regex(
        pattern: "/\D/",
        message: "Faqat raqam qabul qilinadi.",
        match: false
    )]
    #[Groups(['user:read', 'user:write'])]
    private ?string $phone = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'user')]
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }
}
