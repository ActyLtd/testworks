<?php

namespace Acty\Test\Model;

use Acty\Test\Api\OrganizationManagementInterface;
use Magento\Framework\App\ResourceConnection;
use Exception;
use Acty\Test\Model\Organization;

class OrganizationManagement  implements OrganizationManagementInterface
{

   protected $resourceConnection;

   public function __construct(
      ResourceConnection $resourceConnection
   ) {
      $this->resourceConnection = $resourceConnection;
   }

   /**
    * Function to prepare custom array of sql queries and its parameters for given parent and child name
    * This custom array will help execute sql queries
    *
    * @param string $parent_org_name 
    * @param string $org_name 
    * @param string $stmt_org 
    * @param string $stmt_adj_list 
    * @return array
    */
   private function makeInsertQuery($parent_org_name, $org_name, $stmt_org, $stmt_adj_list)
   {
      $query2 = [];
      $query1 = [[
         'stmt' => $stmt_org,
         'arg' => [$org_name]
      ]];

      if ($parent_org_name) {
         $query2 = [[
            'stmt' => $stmt_adj_list,
            'arg' => [$parent_org_name, $org_name]
         ]];
      }
      return array_merge($query1, $query2);
   }

   /**
    * Function to recursivly iterate through model of organization and return custom array of sql queries and its parameters
    * This array is useful in DB transaction to prepare and execute sql queries 
    * $stmt_org is string prepare stateman to insert in organization DB table
    * $stmt_adj_list is string prepare stateman to insert in adjacency_list DB table
    *
    * @param Acty\Test\Model\Organization $org
    * @param string $callback 
    * @param string $stmt_org
    * @param string $stmt_adj_list
    * @param string|null $parent 
    * @return array
    */
   private function iterate_recursive($org, $callback, $stmt_org, $stmt_adj_list, $parent = '')
   {
      $aggregator =  $this->{$callback}($parent, $org->getOrgName(), $stmt_org, $stmt_adj_list);
      if ($org->getDaughters() && is_array($org->getDaughters())) {
         foreach ($org->getDaughters() as $key => $val) {
            $temp = $this->iterate_recursive($val, $callback, $stmt_org, $stmt_adj_list, $org->getOrgName());
            $aggregator = array_merge($aggregator, $temp);
         }
      }

      return $aggregator;
   }

   /**
    * @inheritdoc
    */
   public function insert($org_name, $daughters)
   {

      $org = new Organization;
      $org->setOrgName($org_name);
      $org->setDaughters($daughters);
      $connection = $this->resourceConnection->getConnection();
      $stmt_org = 'INSERT IGNORE INTO `organization`(name) VALUES(?);';
      $stmt_adj_list = 'INSERT IGNORE INTO `adjacency_list`(parent_id, child_id) 
      SELECT p.id, c.id FROM `organization` as p, `organization` as c
      WHERE p.name = ? AND c.name = ?';
      try {
         $result = $this->iterate_recursive($org, 'makeInsertQuery', $stmt_org, $stmt_adj_list);
         $connection->beginTransaction();
         // Loop throught the custom array of prepared statemants and parametars.
         // Each element of this custom array is also array, that contain two elements stmt and arg
         // stmt is the string of prepared statemant for insert
         // arg is array of parametars to bind to prepare statement
         foreach ($result as $query) {
            $stmt = $query['stmt'];
            $connection->query($stmt, $query['arg']);
         }
         $connection->commit();
      } catch (Exception $e) {
         $connection->rollBack();
         $this->resourceConnection->closeConnection();
         throw $e;
      }
      $this->resourceConnection->closeConnection();
   }
}
