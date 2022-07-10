<?php

namespace Acty\Test\Api;

/**
 * GET Ogranization list by organization name
 * @api 
 */
interface OrganizationListInterface
{
    /**
     * @param string $organization
     * @param int $pageNumber
     * @return Acty\Test\Model\OrganizationList[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList($organization, $pageNumber);
}
