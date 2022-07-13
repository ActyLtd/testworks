<?php
 
namespace Rainari\ActyRest\Model\ResourceModel\OrganizationCollections;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Rainari\ActyRest\Model\Organization',
            'Rainari\ActyRest\Model\ResourceModel\OrganizationCollections'
        );
    }
}