<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-11-26
 * Time: 16:30
 */

namespace App\Domain\Post\Event;


use App\Domain\Post\ValueObject\User;
use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PostWasCreated implements Serializable
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

    public function __construct(
        UuidInterface $id,
        string $title,
        string $description
    ) {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }


    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new self(
            Uuid::fromString($data['id']),
            $data['title'],
            $data['description']
        );
    }

    /**
     * @return array
     */
    public function serialize() :array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description
        ];
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}