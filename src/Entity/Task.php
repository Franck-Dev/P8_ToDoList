<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int|null|null
     */
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    /**
     * @var \DateTimeInterface|null|null
     */
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Vous devez saisir un titre.")]
    /**
     * @var string|null|null
     */
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Vous devez saisir du contenu.")]
    /**
     * @var string|null|null
     */
    private ?string $content = null;

    #[ORM\Column]
    /**
     * @var bool|null|null
     */
    private ?bool $isDone = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)]
    /**
     * @var User|null|null
     */
    private ?User $userCreat = null;

        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
        
    }
    
    
    /**
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * 
     * @return static
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * 
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * 
     * @return static
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isIsDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @param bool $isDone
     * 
     * @return static
     */
    public function setIsDone(bool $isDone): static
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isDone()
    {
        return $this->isDone;
    }

    /**
     * @param mixed $flag
     * 
     * @return bool
     */
    public function toggle($flag)
    {
        $this->isDone = $flag;
    }

    /**
     * @return User|null
     */
    public function getUserCreat(): ?User
    {
        return $this->userCreat;
    }

    /**
     * @param User|null $userCreat
     * 
     * @return static
     */
    public function setUserCreat(?User $userCreat): static
    {
        $this->userCreat = $userCreat;

        return $this;
    }
}
