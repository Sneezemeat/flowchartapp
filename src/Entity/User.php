<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @var array<string>
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Diagram", mappedBy="user", orphanRemoval=true)
     * @psalm-var Collection<int, Diagram>
     */
    private Collection $diagrams;

    /**
     * @ORM\Column(type="integer")
     */
    private int $level;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $usedMobile;

    /**
     * User constructor.
     *
     * @param string $email
     * @param string $password
     * @param int    $level
     */
    public function __construct(string $email, string $password, int $level)
    {
        $this->id       = Uuid::uuid4();
        $this->diagrams = new ArrayCollection();
        $this->email    = $email;
        $this->password = $password;
        $this->level    = $level;
        $this->roles    = ['ROLE_USER'];
        $this->usedMobile = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getName(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array<string> $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = \array_unique($roles);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @psalm-return Collection<int, Diagram>
     */
    public function getDiagrams(): Collection
    {
        return $this->diagrams;
    }

    public function addDiagram(Diagram $diagram): self
    {
        if (!$this->diagrams->contains($diagram)) {
            $this->diagrams[] = $diagram;
            $diagram->setUser($this);
        }

        return $this;
    }

    public function removeDiagram(Diagram $diagram): self
    {
        if ($this->diagrams->contains($diagram)) {
            $this->diagrams->removeElement($diagram);
        }

        return $this;
    }


    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function increaseLevel(): void
    {
        ++$this->level;
    }

    /**
     * @return bool
     */
    public function getUsedMobile()
    {
        return $this->usedMobile;
    }

    /**
     * @param bool $usedMobile
     */
    public function setUsedMobile($usedMobile): void
    {
        $this->usedMobile = $usedMobile;
    }


}
