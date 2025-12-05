<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\User;
use DateTimeZone;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHarsher
    ) {
    }

    public function create(
        string $userName,
        string $password,
        string $email,
        int $age,
        string $gender,
        string $phone
    ): User {
        $user = new User();

        $hashedPassword = $this->passwordHarsher->hashPassword($user, $password);

        $user->setUsername($userName);
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setAge($age);
        $user->setGender($gender);
        $user->setPhone($phone);
        $user->setRoles(["ROLE_USER"]);
        $user->setCreatedAt(
            new DatePoint(timezone: new DateTimeZone('Asia/Tashkent'))
        );

        return $user;
    }
}