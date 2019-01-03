<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 15:47
 */

namespace App\Domain\Post;

use App\Domain\Post\Event\PostWasCreated;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

class Post extends EventSourcedAggregateRoot
{

    private $id;
    private $title;
    private $description;

    public static function create(
        UuidInterface $id,
        string $title,
        string $description
    ) {
        $demo = new self();

        $demo->apply(new PostWasCreated(
            $id,
            $title,
            $description)
        );

        return $demo;
    }

    protected function applyPostWasCreated(PostWasCreated $postCreated)
    {
        $this->id = $postCreated->getId();
        $this->title = $postCreated->getTitle();
        $this->description = $postCreated->getDescription();
    }

    /**
     * @return string
     */
    public function getAggregateRootId(): string
    {
        return $this->id->toString();
    }
}