<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 15:40
 */

namespace App\Domain\Post\Command;


use App\Domain\Post\ValueObject\User;
use Ramsey\Uuid\UuidInterface;

class CreatePost
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