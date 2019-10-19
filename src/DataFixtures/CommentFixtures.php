<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends AppFixtures implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [PostFixtures::class, UserFixtures::class];
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(
            100,
            'main_comments',
            function () {
                $comment = new Comment();
                $comment->setContent(
                    $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
                );


                $comment->setPublished($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

                $comment->setAuthor($this->getRandomReference('main_users'));
                $comment->setPost($this->getRandomReference('main_posts'));

                return $comment;
            }
        );

        $manager->flush();
    }
}
