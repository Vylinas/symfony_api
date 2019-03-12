<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;


class UserService 
{
    /**
     * @var ObjectManager
     */
    private $em;


    /**
     * @var UserPasswordEncoderInterface
     */
    private $hasher;

    public function __construct(
        ObjectManager $em,
        UserPasswordEncoderInterface $hasher)
    {
        $this->em       = $em;
        $this->hasher   = $hasher;
    }

    /**
     * @param User
     */
    public function create(User $user)
    {
        if (!$user->setPassword($this->hasher->encodePassword($user, $user->getPassword())) ||
            !$user->setCreatedAt(new \DateTime()) ||
            !$user->setIsActive(true)
        ){ throw new Exception('Les value ne sont pas correct'); }
        $this->em->persist($user);
        $this->em->flush($user);
    }

    /**
     * @param User the user to update
     * @param User the user with new proprety
     */
    public function update(User $user, User $editUser)
    {
        if($editUser->getEmail() !== $user->getEmail())
        {
            $user->setEmail($editUser->getEmail());
        }
        $user->setUpdatedAt(new \DateTime("now"));
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}