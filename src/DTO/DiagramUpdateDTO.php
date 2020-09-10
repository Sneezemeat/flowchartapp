<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Diagram;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DiagramUpdateDTO.
 */
class DiagramUpdateDTO
{
    /**
     * @Assert\Length(min = 2, max = 50)
     * @Assert\NotNull()
     */
    public string $name;

    public string $data;

    /**
     * DiagramUpdateDTO constructor.
     */
    public function __construct(string $name, string $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Converts the DTO to a Diagram Entity.
     */
    public function toEntity(Diagram $diagram, User $user): Diagram
    {
        $diagram->setName($this->name);
        $diagram->setData($this->data);
        $diagram->setUser($user);

        return $diagram;
    }

    /**
     * Converts a Diagram DTO to an Entity.
     *
     * @return DiagramUpdateDTO
     */
    public static function fromEntity(Diagram $diagram): self
    {
        return new self($diagram->getName(), $diagram->getData());
    }
}
