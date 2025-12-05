<?php

declare(strict_types=1);

namespace App\Controller\user;

use App\Component\User\UserManager;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserChangeAction extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHarsher
    ) {
    }

    public function __invoke(
        User $data,
        Request $request,
        UserManager $userManager,
        UserRepository $userRepository,
    ): User
    {
        $userId = $request->query->get('id');
        $user = $userRepository->find($userId);

        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        if ($data->getPassword()) {
            $hashedPassword = $this->passwordHarsher->hashPassword($user, $data->getPassword());

            $user->setUsername($data->getUsername());
            $user->setPassword($hashedPassword);
            $user->setAge($data->getAge());
            $user->setPhone($data->getPhone());
        }

        $userManager->save($user, true);

        return $user;
    }
}
