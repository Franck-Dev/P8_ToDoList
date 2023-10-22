<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]


    /**
     * @var int|null|null
     */
    private ?int $id = null;

    #[ORM\Column(length: 25, unique:true)]
    #[Assert\NotBlank(message:"Vous devez saisir un nom utilisateur.")]
    /**
     * @var string|null|null
     */
    private ?string $username = null;

    #[ORM\Column(length: 64)]
    /**
     * @var string|null|null
     */
    private ?string $password = null;

    #[ORM\Column(length: 60, unique:true)]
    #[Assert\NotBlank(message:"Vous devez saisir une adresse email.")]
    #[Assert\Email(message:"Le format de l'adresse n'est pas correcte.")]
    /**
     * @var string|null|null
     */
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'userCreat', targetEntity: Task::class)]
    /**
     * @var Collection
     */
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    
    /**
     * getUsername
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    /**
     * setUsername
     *
     * @param  mixed $username
     * @return static
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
    
    /**
     * getPassword
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * setPassword
     *
     * @param  mixed $password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * getEmail
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * setEmail
     *
     * @param  mixed $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * getRoles
     *
     * @return void
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
    
    /**
     * addTask
     *
     * @param  mixed $task
     * @return static
     */
    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUserCreat($this);
        }

        return $this;
    }
    
    /**
     * removeTask
     *
     * @param  mixed $task
     * @return static
     */
    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUserCreat() === $this) {
                $task->setUserCreat(null);
            }
        }

        return $this;
    }
}
