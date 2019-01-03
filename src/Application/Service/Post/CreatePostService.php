<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-12-11
 * Time: 10:33
 */

namespace App\Application\Service\Post;


use App\Domain\Post\Command\CreatePost;
use Broadway\CommandHandling\CommandBus;
use Ramsey\Uuid\Uuid;

class CreatePostService
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function execute(string $title, string $description)
    {
        $command = new CreatePost(
            Uuid::uuid4(),
            $title,
            $description
        );

        $this->commandBus->dispatch($command);
    }
}