<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Component\Book\BookInformationDto;
use App\Controller\book\BookChangeLikesAction;
use App\Controller\book\BookInformationAction;
use App\Controller\book\GetBooksByCategoryAction;
use App\Controller\book\GetBooksByLikeAction;
use App\Controller\book\GetBooksHardExampleAction;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'books/by-category',
            controller: GetBooksByCategoryAction::class,
            name: 'getBooks',
            parameters: [
                'categoryId' => new QueryParameter(schema: ['type' => 'integer'])
            ]
        ),new GetCollection(
            uriTemplate: 'books/by-like',
            controller: GetBooksByLikeAction::class,
            name: 'getBooksByLike',
            parameters: [
                'username' => new QueryParameter(schema: ['type' => 'string '])
            ]
        ),
        new GetCollection(
            uriTemplate: 'books/hard-example',
            controller: GetBooksHardExampleAction::class,
            name: 'getHardExample',
        ),
        new Post(
            uriTemplate: 'books/information',
            controller: BookInformationAction::class,
            input: BookInformationDto::class,
            name: 'information'
        ),
        new Patch(
            uriTemplate: 'books/change_likes',
            controller: BookChangeLikesAction::class,
            name: 'changeLikes',
            parameters: [
                "id" => new QueryParameter(schema: ['type' => 'integer']),
                "username" => new QueryParameter(schema: ['type' => 'string '])
            ]
        ),
        new GetCollection(),
        new Get(),
        new Post(),
        new Delete(),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['book:read']],
    denormalizationContext: ['groups' => ['book:write']],
    paginationItemsPerPage: 8
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['book:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read', 'book:write', ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['book:read', 'book:write'])]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read', 'book:write'])]
    private ?Category $category = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read', 'book:write'])]
    private ?MediaObject $image = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'book')]
    #[Groups(['book:read'])]
    private Collection $comment;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'book')]
    private Collection $likes;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(MediaObject $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setBook($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBook() === $this) {
                $comment->setBook(null);
            }
        }

        return $this;
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
            $like->setBook($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getBook() === $this) {
                $like->setBook(null);
            }
        }

        return $this;
    }
}
