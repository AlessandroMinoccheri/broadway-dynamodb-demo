<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 15:45
 */

namespace App\Infrastructure\Repository;


use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use App\Domain\Post\Post;

class PostRepository extends EventSourcingRepository
{
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            Post::class,
            new PublicConstructorAggregateFactory());
    }


}

