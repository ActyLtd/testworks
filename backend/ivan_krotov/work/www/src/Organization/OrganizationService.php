<?php

namespace root\Organization;

class OrganizationService
{
    private OrganizationGateway $gateway;

    public function __construct()
    {
        $this->gateway = new OrganizationGateway();
    }

    public function createNew(OrganizationBean $bean)
    {
        $organization = new Organization();
        $organization->setName($bean->getOrgName());
        $id = $organization->save();
        if ($bean->getDaughters()) {
            $this->saveDaughters($bean->getDaughters(), $id);
        }
    }

    /**
     * @param OrganizationBean[] $daughters
     * @param int $parentId
     * @return void
     */
    private function saveDaughters(array $daughters, int $parentId)
    {
        foreach ($daughters as $daughter) {
            $daughterOrg = new Organization();
            $daughterOrg->setName($daughter->getOrgName());
            $daughterOrg->setParent($parentId);
            $id = $daughterOrg->save();
            if ($daughter->getDaughters()) {
                $this->saveDaughters($daughter->getDaughters(), $id);
            }
        }
    }

    private function getDaughters(int $parentId): array
    {
        $result = [];
        $res = $this->gateway->getDaughters($parentId);
        if ($res) {
            foreach ($res as $item) {
                $org = new Organization($item);
                $result[] = $this->prepareBean($org);
            }
        }
        return $result;
    }

    private function getSisters(int $id): array
    {
        return $this->getDaughters($id);
    }

    private function getParents(int $id): array
    {
        $result = [];
        $res = $this->gateway->getParent($id);
        if ($res) {
            $org = new Organization($res);
            $result[] = $this->prepareBean($org);
        }
        return $result;
    }

    private function prepareBean(Organization $org): OrganizationBean
    {
        $resBean = new OrganizationBean();
        $resBean->setId($org->getId());
        $resBean->setOrgName($org->getName());
        $resBean->setDaughters($this->getDaughters($org->getId()));
        return $resBean;
    }

    /**
     * @param OrganizationBean $bean
     * @return array|OrganizationBean|null
     */
    public function find(OrganizationBean $bean)
    {
        $result = null;
        if ($bean->getId()) {
            $org = new Organization($bean->getId());
            $result = $this->prepareBean($org);
        } else if ($bean->getOrgName()) {
            $res = $this->gateway->findByLikeName($bean->getOrgName());
            if ($res) {
                foreach ($res as $item) {
                    $org = new Organization($item);
                    $result[] = $this->prepareBean($org);
                }
            }
            return $result;

        }
        return $result;
    }

    /**
     * @param OrganizationBean $bean
     * @return OrganizationRelationBean[]
     */
    public function findRelations(OrganizationBean $bean, $offset = 0, $limit = 100): array
    {
        $result = [];
        $org = null;

        if ($bean->getId()) {
            $org = new Organization($bean->getId());
        } else if ($bean->getOrgName()) {
            $res = $this->gateway->findByName($bean->getOrgName());
            $org = new Organization($res);
        }

        if ($org) {
            $parents = $this->getParents($org->getParent());
            $sisters = $this->getSisters($org->getParent());
            $daughters = $this->getDaughters($org->getId());

            $result = array_merge(
                $this->prepareRelationBean($parents, OrganizationRelationTypes::PARENT),
                $this->prepareRelationBean($sisters, OrganizationRelationTypes::SISTER),
                $this->prepareRelationBean($daughters, OrganizationRelationTypes::DAUGHTER),
            );

            uasort($result, function($a, $b) {
                return $a->getOrgName() > $b->getOrgName();
            });

            $result = array_splice($result, $offset, $limit);
        }
        return $result;
    }

    /**
     * @param OrganizationBean[] $orgs
     * @param string $relationType
     * @return OrganizationRelationBean[]
     */
    private function prepareRelationBean(array $orgs, string $relationType): array
    {
        $result = [];
        foreach ($orgs as $org) {
            $bean = new OrganizationRelationBean();
            $bean->setOrgName($org->getOrgName());
            $bean->setRelationType($relationType);
            $result[] = $bean;
        }
        return $result;
    }
}