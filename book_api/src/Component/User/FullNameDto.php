<?php

declare(strict_types=1);

namespace App\Component\User;

use Symfony\Component\Serializer\Annotation\Groups;

readonly class FullNameDto
{
    public function __construct(
        #[Groups(['user:write', 'user:read'])]
        private string $firstName,

        #[Groups(['user:write'])]
        private string $lastName,

        #[Groups(['user:write', 'user:read'])]
        private bool $isMarried
    )
    {}

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getIsMarried(): bool
    {
        return $this->isMarried;
    }
}
