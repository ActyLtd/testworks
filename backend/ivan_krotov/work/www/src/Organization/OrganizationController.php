<?php

namespace root\Organization;

use root\Base\Controller;

class OrganizationController extends Controller
{
    private OrganizationService $service;

    public function __construct()
    {
        $this->service = new OrganizationService();
    }

    public static function getInstance(): OrganizationController
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addNew()
    {
        $postArray = json_decode($_POST['organizations']);
        foreach ($postArray as $item) {
            $orgBean = new OrganizationBean($item);
            $this->service->createNew($orgBean);
        }
    }

    public function get(array $data)
    {
        $bean = new OrganizationBean((object)$data);
        $res = $this->service->find($bean);
        echo json_encode($res);
    }

    public function getRelations(array $data)
    {
        $bean = new OrganizationBean((object)$data);
        $limit = $data['limit']??100;
        $offset = $data['offset']??0;
        $res = $this->service->findRelations($bean, $offset, $limit);
        echo json_encode($res);
    }
}