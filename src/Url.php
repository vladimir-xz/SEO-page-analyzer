<?php

namespace Hexlet\Code;

class Url
{
    protected int $id;
    protected ?string $name;
    protected ?string $createdAt;

    public function __construct(array $record)
    {
        $this->id = $record['id'];
        $this->name = $record['name'];
        $this->createdAt = $record['created_at'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
