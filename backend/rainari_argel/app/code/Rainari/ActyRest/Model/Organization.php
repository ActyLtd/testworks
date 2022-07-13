<?php
namespace Rainari\ActyRest\Model;

use Rainari\ActyRest\Api\OrganizationInterface;
use Magento\Framework\Model\AbstractModel;

use Rainari\ActyRest\Model\ResourceModel\OrganizationCollections as OrganizationResource;
use \Magento\Framework\Webapi\Rest\Request as RestRequest;


class Organization extends AbstractModel implements OrganizationInterface
{

    /**
     * @var WarehouseResource
     */
    private $organizationResource;

    protected $request;

    private $response = [];

    private $data = [];
    /**
     * Define resource model
     */
    public function __construct(
        OrganizationResource $organizationResource,
        RestRequest $request
    ) {
        $this->organizationResource = $organizationResource;
        $this->request = $request;
    }

    public function getOrganization(string $name, int $page = 1)
    {
        $object = $this->organizationResource->getOrganizationData($name);
        if($object && $childs = $this->organizationResource->getAllChilds($object['id'], $page)) : 
            foreach($childs as $child) :
                $this->data[] = [
                    'relationship_type' => $child['relationship_type'],
                    'org_name' => $child['child_name']
                ];
            endforeach;
        endif;
    
        return $this->data;
    }

    public function postOrganization()
    {
        $this->response['status'] = false;
        if(empty($this->request->getContent())) {
            return;
        }

        $postData = json_decode($this->request->getContent());
        $insertData = [];
        if(is_object($postData) && $postData->org_name) {
            $parents = $postData->parents;
            $organizationID = $this->organizationResource->postOrganization(['organization_name' => $postData->org_name]);
            if(count($parents) > 0 && (int)$organizationID) {
                $this->response['status'] = true;
                foreach($parents as $parent):
                    $insertData[] = [
                        'organization_id' => $organizationID,
                        'child_name' => $parent->org_name,
                        'relationship_type' => 'parent'
                    ];
                    $sisters = (isset($parent->sisters) ? $parent->sisters : []);
                    if(count($sisters) > 0) {
                        foreach($sisters as $sister):
                            $insertData[] = [
                                'organization_id' => $organizationID,
                                'child_name' => $sister->org_name,
                                'relationship_type' => 'sister'
                            ];

                            $daughters = (isset($sister->daughters) ? $sister->daughters : []);
                            if(count($daughters) > 0) {
                                foreach($daughters as $daughter):
                                    $insertData[] = [
                                        'organization_id' => $organizationID,
                                        'child_name' => $daughter->org_name,
                                        'relationship_type' => 'daughters'
                                    ];
                                endforeach;
                            }
                        endforeach;
                    }
                    
                endforeach;
            }

            if(count($insertData) > 0) {
                if($this->organizationResource->postOrganizationDaughters($insertData)) :
                    $this->response['status'] = true;
                endif;
            }
        }

        return [$this->response];
    }

}