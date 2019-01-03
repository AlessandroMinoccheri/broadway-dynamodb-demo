<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 16:02
 */

namespace App\Domain\Post\ReadModel;


use App\Domain\Post\ReadModel\PostReadModelRepository;
use App\Domain\Post\ValueObject\User;
use Broadway\ReadModel\Identifiable;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreatePostReadModel implements Identifiable, Serializable
{

    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    private $occurredAt;

    public function __construct(
        UuidInterface $id,
        string $title,
        string $description,
        string $occurredAt = null
    ) {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;

        if ($occurredAt === null) {
            $this->occurredAt = date('Y-m-d H:i:s');
        } else {
            $this->occurredAt = $occurredAt;
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->toString();
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(
            Uuid::fromString($data['id']),
            $data['title'],
            $data['description'],
            $data['occurred_at']
        );
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'occurred_at' => $this->occurredAt
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return false|string
     */
    public function getOccurredAt()
    {
        return $this->occurredAt;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}