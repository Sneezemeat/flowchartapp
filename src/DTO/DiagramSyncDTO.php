<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Diagram;
use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DiagramDTO.
 */
class DiagramSyncDTO
{
    public string $id;

    /**
     * @Assert\Length(min = 2, max = 50)
     * @Assert\NotNull()
     */
    public string $name;

    public string $data;

    /**
     * DiagramDTO constructor.
     *
     * @param string $id
     * @param string        $name
     * @param string        $data
     */
    public function __construct(string $id, string $name, string $data)
    {
        $this->id = $id;
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Converts the DTO to a Diagram Entity.
     *
     * @param User $user
     *
     * @return Diagram
     */
    public function toEntity(User $user): Diagram
    {
        $diagram = new Diagram($this->name, $this->data, $user);
        $diagram->setId(Uuid::fromString($this->id));
        return $diagram;
    }

    /**
     * Converts a Diagram DTO to an Entity.
     *
     * @param Diagram $diagram
     *
     * @return DiagramSyncDTO
     */
    public static function fromEntity(Diagram $diagram): self
    {
        return new self($diagram->getId()->toString(), $diagram->getName(), $diagram->getData());
    }
}
