<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Diagram;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class UserDTO.
 *
 * @Assert\Callback("equalPassword")
 */
class UserAddDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Email
     */
    public string $email;

    /**
     * @var array<string>
     */
    public array $roles;

    /**
     * @Assert\NotNull
     * @Assert\Length(min=8)
     */
    public string $password;

    /**
     * @Assert\NotNull
     * @Assert\Length(min=8)
     */
    public string $passwordConfirmation;

    /**
     * @var array<Diagram>
     */
    public array $diagrams;

    public int $level;

    public bool $isAdmin;

    /**
     * UserDTO constructor.
     */
    public function __construct(string $email, string $password, int $level, bool $isAdmin)
    {
        $this->email    = $email;
        $this->password = $password;
        $this->level    = $level;
        $this->roles    = [];
        $this->diagrams = [];
        $this->isAdmin  = $isAdmin;
    }

    /**
     * Converts the current userDTO to a user entity.
     */
    public function toEntity(): User
    {
        $user = new User($this->email, $this->password, $this->level);

        if (isset($this->diagrams)) {
            foreach ($this->diagrams as $diagram) {
                $user->addDiagram($diagram);
            }
        }

        return $user;
    }

    public function equalPassword(ExecutionContextInterface $context): void
    {
        if ($this->password !== $this->passwordConfirmation) {
            $context->addViolation('Password confirmation is not equal to password');
        }
    }
}
