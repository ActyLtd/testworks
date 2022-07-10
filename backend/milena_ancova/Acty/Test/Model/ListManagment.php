<?php

declare(strict_types=1);

namespace Acty\Test\Model;

use Acty\Test\Api\OrganizationListInterface;
use Magento\Framework\App\ResourceConnection;
use Exception;
use Acty\Test\Model\OrganizationList;

class ListManagment implements OrganizationListInterface
{

   protected $organizationList;
   protected $resourceConnection;

   public function __construct(
      ResourceConnection $resourceConnection,
      organizationList $organizationList
   ) {
      $this->resourceConnection = $resourceConnection;
      $this->organizationList = $organizationList;
   }

   /**
    * List of all organization that are related with the organization with given organization name
    * Pagination included to return max 100 records per page  
    * @inheritdoc
    */

   public function getList($organization,  $pageNumber)
   {
      if (isset($pageNumber)) {
         $page =  $pageNumber;
      } else {
         $page = 1;
      }
      $no_of_records_per_page = 100;
      $offset = ($page - 1) * $no_of_records_per_page;
      $connection = $this->resourceConnection->getConnection();
      $total_pages_sql = "SELECT COUNT(*) FROM table";
      $query = "SELECT org_child.name AS org_name, 'daughter' AS relation_type 
         FROM `adjacency_list` AS adj_l 
         JOIN `organization` AS org_child ON adj_l.child_id = org_child.id 
         JOIN `organization` AS org_parent ON adj_l.parent_id = org_parent.id AND org_parent.name = ?
         UNION ALL
         SELECT DISTINCT org_child.name AS org_name, 'sister' as relation_type 
         FROM `adjacency_list` AS adj_l 
         JOIN `organization` AS org_child ON adj_l.child_id = org_child.id AND org_child.name != ?
         WHERE adj_l.parent_id IN (
            SELECT adj_l_s.parent_id FROM `adjacency_list` AS adj_l_s
            JOIN `organization` AS org_child ON child_id = org_child.id AND org_child.name = ?)
         UNION ALL
         SELECT org_parent.name AS org_name, 'parent' as relation_type 
         FROM `adjacency_list` AS adj_l 
         JOIN `organization` AS org_parent ON adj_l.parent_id = org_parent.id 
         JOIN `organization` AS org_child ON adj_l.child_id = org_child.id AND org_child.name = ?
         ORDER BY org_name
         LIMIT  $offset,  $no_of_records_per_page ";
      try {
         $array = [];
         $result = $connection->fetchAll($query, [$organization, $organization, $organization, $organization]);
         if (!empty($result)) {
            foreach ($result as $data) {
               $this->organizationList =  new OrganizationList();
               $this->organizationList->setOrgName($data['org_name']);
               $this->organizationList->setRelationType($data['relation_type']);
               array_push($array, $this->organizationList);
            }
            return $array;
         } else {
            echo 'There is no results with ' . $organization . ' organization name';
         }
      } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
         throw $exception;
      } catch (\Magento\Framework\Exception\LocalizedException $exception) {
         throw $exception;
      }
      $this->resourceConnection->closeConnection();
   }
}
