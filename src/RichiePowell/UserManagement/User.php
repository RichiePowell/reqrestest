<?php

namespace RichiePowell\UserManagement;

use JsonSerializable;

class User implements JsonSerializable
{
    private int $id;
    private string $name;
    private string|null $job;

    public function __construct(int $id, string $name, string|null $job)
    {
        $this->id = $id;
        $this->name = $name;
        $this->job = $job;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getJob()
    {
        return $this->job;
    }

    /**
     * Convert the User to an array for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'job' => $this->job,
        ];
    }
}