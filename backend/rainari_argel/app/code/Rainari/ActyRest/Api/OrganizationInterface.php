<?php
namespace Rainari\ActyRest\Api;

interface OrganizationInterface
{
    /**
    * GET Organization Data
    * @param string $name
    * @param int $page
    * @return array
    */
    public function getOrganization(string $name, int $page = 1);
 
    /**
    * POST Organization Data
    * @return array
    */
    public function postOrganization();
}
