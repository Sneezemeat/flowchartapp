<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Diagram;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DiagramDTO.
 */
class DiagramDTO
{
    /**
     * @Assert\Length(min = 2, max = 50)
     * @Assert\NotNull()
     */
    public string $name;

    public string $data;

    /**
     * DiagramDTO constructor.
     */
    public function __construct(string $name, string $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Converts the DTO to a Diagram Entity.
     */
    public function toEntity(User $user): Diagram
    {
        return new Diagram($this->name, $this->data, $user);
    }

    /**
     * Converts a Diagram DTO to an Entity.
     *
     * @return DiagramDTO
     */
    public static function fromEntity(Diagram $diagram): self
    {
        return new self($diagram->getName(), $diagram->getData());
    }
}
