<?php

namespace Acty\Test\Api;

/**
 * POST to insert ogranizations and relations
 * @api 
 */
interface OrganizationManagementInterface
{
    /**
     * @param string $org_name
     * @param Acty\Test\Model\Organization[] $daughters
     * @return mixed
     */
    public function insert($org_name, $daughters);
}
