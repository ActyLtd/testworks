<?php

namespace root\Organization;

class OrganizationRelationBean
{
    public string $relation_type;
    public string $org_name;

    /**
     * @return string
     */
    public function getRelationType(): string
    {
        return $this->relation_type;
    }

    /**
     * @param string $relation_type
     */
    public function setRelationType(string $relation_type): void
    {
        $this->relation_type = $relation_type;
    }

    /**
     * @return string
     */
    public function getOrgName(): string
    {
        return $this->org_name;
    }

    /**
     * @param string $org_name
     */
    public function setOrgName(string $org_name): void
    {
        $this->org_name = $org_name;
    }
}