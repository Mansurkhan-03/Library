<?php

declare(strict_types=1);

namespace App\Controller\user;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\UserManager;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserChangePasswordAction extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHarsher
    ) {}

    public function __invoke(
        User $data,
        Request $request,
        UserManager $userManager,
        UserRepository $userRepository,
        ValidatorInterface $validator

    ): User {
        $validator->validate($data);

        $userEmail = $request->query->get('email');
        $user = $userRepository->findOneBy(['email' => $userEmail]);

        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        if ($data->getPassword() !== null) {
            $hashedPassword = $this->passwordHarsher->hashPassword($user, $data->getPassword());
            $user->setPassword($hashedPassword);
        }

        $userManager->save($user, true);

        return $user;
    }
}

//$userId = $request->query->get('id');
//$user = $entityManager->getRepository(User::class)->find($userId);
//
//if (!$user) {
//    throw new BadRequestHttpException('User not found');
//}
//
//$data = json_decode($request->getContent(), true);
//
//if (isset($data['password'])) {
//    $hashedPassword = $this->passwordHarsher->hashPassword($user, $data['password']);
//    $user->setPassword($hashedPassword);
//}
//
//$entityManager->persist($user);
//$entityManager->flush();
//
//return $user;
