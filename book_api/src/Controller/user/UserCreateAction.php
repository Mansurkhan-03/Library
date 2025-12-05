<?php

declare(strict_types=1);

namespace App\Controller\user;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserCreateAction extends AbstractController
{
    public function __invoke(
        User $data,
        UserFactory $factory,
        UserManager $manager,
        ValidatorInterface $validator
    ): User {
        $validator->validate($data);

        $user = $factory->create(
            $data->getUsername(),
            $data->getPassword(),
            $data->getEmail(),
            $data->getAge(),
            $data->getGender(),
            $data->getPhone()
        );

        $manager->save($user, true);

        return $user;
    }
}
