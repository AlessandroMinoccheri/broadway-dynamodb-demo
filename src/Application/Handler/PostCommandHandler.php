<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 15:42
 */

namespace App\Application\Handler;

use App\Domain\Post\Command\CreatePost;
use App\Domain\Post\Post;
use Broadway\CommandHandling\SimpleCommandHandler;
use Broadway\Repository\Repository;

class PostCommandHandler extends SimpleCommandHandler
{
    /**
     * @var Repository
     */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handleCreatePost(CreatePost $command)
    {
        $post = Post::create(
            $command->getId(),
            $command->getTitle(),
            $command->getDescription()
        );

        $this->repository->save($post);
    }
}