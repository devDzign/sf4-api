<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AppFixtures
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(
            10,
            'main_users',
            function ($i) use ($manager) {
                $user = new User();
                $user->setEmail(sprintf('user%d@test.com', $i));
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        'admin'
                    )
                );


                return $user;
            }
        );

        $manager->flush();
    }
}
