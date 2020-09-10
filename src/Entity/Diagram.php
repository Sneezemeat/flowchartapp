<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiagramRepository")
 */
class Diagram
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @Groups("list")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("list")
     */
    private string $name;

    /**
     * @ORM\Column(type="json")
     */
    private string $data;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="diagrams")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * Diagram constructor.
     */
    public function __construct(string $name, string $data, User $user)
    {
        $this->id   = Uuid::uuid4();
        $this->name = $name;
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * @param UuidInterface $id
     *
     * @return $this
     */
    public function setId(UuidInterface $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
