<?php

namespace root\Organization;

class OrganizationBean
{
    public ?int $id = null;
    public ?string $org_name;
    public ?array $daughters = [];

    public function __construct(object $data = null)
    {
        if ($data) {
            $this->setOrgName($data->org_name);
            $this->setId($data->id);
            $daughters = $data->daughters;
            $res = [];
            if (!empty($daughters)) {
                foreach ($daughters as $daughter) {
                    $res[] = new OrganizationBean($daughter);
                }
                $this->setDaughters($res);
            }
        }
    }

    /**
     * @return string
     */
    public function getOrgName(): string
    {
        return $this->org_name;
    }

    /**
     * @param string|null $org_name
     */
    public function setOrgName(?string $org_name): void
    {
        $this->org_name = $org_name;
    }

    /**
     * @return OrganizationBean[]
     */
    public function getDaughters(): array
    {
        return $this->daughters;
    }

    /**
     * @param OrganizationBean[] $daughters
     */
    public function setDaughters(array $daughters): void
    {
        $this->daughters = $daughters;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }


}