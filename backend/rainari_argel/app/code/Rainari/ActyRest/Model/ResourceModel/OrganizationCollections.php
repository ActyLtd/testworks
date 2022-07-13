<?php
 
namespace Rainari\ActyRest\Model\ResourceModel;
 
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Throwable;

class OrganizationCollections extends AbstractDb
{

    private $adapter;

    private $maxOnPage = 4;
    private $pageNumber = 0;

    protected function _construct()
    {
        $this->adapter = $this->getConnection();
    }

    public function getOrganizationData(string $name) 
    {
        $select = $this->adapter->select()->from(
            [$this->getTable('rainari_restapi_organizations')],
            ['*']
        )->where(
            'organization_name = ?', $name
        );
        
        return $this->adapter->fetchRow($select);

    }

    public function getAllChilds($parent_id, int $page) {
        $this->pageNumber = ($page > 1) ? ($this->maxOnPage * ($page - 1)) : 0;
        $select = $this->adapter->select()->from(
            [$this->getTable('rainari_restapi_organization_childs')],
            ['*']
        )->where(
            'organization_id = ?', $parent_id
        )->order('child_name ASC')->limit($this->maxOnPage, $this->pageNumber);
        
        return $this->adapter->fetchAll($select);
    }

    public function postOrganization($data) {
        try {
            $this->adapter->insert('rainari_restapi_organizations', $data);
            return $this->adapter->lastInsertId('rainari_restapi_organizations', 'id');
        } catch (Throwable $e) {
            return;
        }
    }

    public function postOrganizationDaughters($data) {
        try {
            $this->adapter->insertArray('rainari_restapi_organization_childs', ['organization_id' , 'child_name', 'relationship_type'], $data);
        } catch (Throwable $e) {
            return;
        }
    }

}