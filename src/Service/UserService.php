<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;


class UserService 
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
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
        
        $this->em->persist($user);
        $this->em->flush();
    }

}