<?php

namespace root\Organization;

use root\Base\Entity;

class Organization extends Entity
{
    protected static string $table = 'organization';
    protected static string $idName = 'id';

    public ?int $id = null;
    public string $name;
    public int $parent = 0;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getParent(): int
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     */
    public function setParent(int $parent): void
    {
        $this->parent = $parent;
    }

    protected function setId(int $val): void
    {
        $this->id = $val;
    }
}