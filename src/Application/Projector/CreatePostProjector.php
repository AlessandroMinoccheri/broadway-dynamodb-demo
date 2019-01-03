<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 16:00
 */

namespace App\Application\Projector;


use App\Domain\Post\Event\PostWasCreated;
use App\Domain\Post\ReadModel\CreatePostReadModel;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\Repository;

class CreatePostProjector extends Projector
{
    /**
     * @var Repository
     */
    private $createPostRepository;

    public function __construct(
        Repository $createPostRepository
    ) {
        $this->createPostRepository = $createPostRepository;
    }

    public function applyPostWasCreated(PostWasCreated $postWasCreated)
    {
        $now = new \DateTime();
        $now = $now->format('Y-m-d H:i:s');

        $this->createPostRepository->save(
            new CreatePostReadModel(
                $postWasCreated->getId(),
                $postWasCreated->getTitle(),
                $postWasCreated->getDescription(),
                $now
            )
        );
    }
}