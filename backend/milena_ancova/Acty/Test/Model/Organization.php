<?php
namespace Acty\Test\Model;

class Organization implements \JsonSerializable
{ 
     /**
     * @return  mixed
     */
    public function jsonSerialize()
    {
            return get_object_vars($this);
    }
    /**
     * @var string
     */
    protected $org_name;

    /**
     * @var Acty\Test\Model\Organization[]
     */
    protected $daughters;

    /**
     * Get the value of daughters
     *
     * @return  Acty\Test\Model\Organization[]
     */
    public function getDaughters()
    {
        return $this->daughters;
    }

    /**
     * Set the value of ClaimItems
     *
     * @param  Acty\Test\Model\Organization[]  $daughters
     *
     * @return  self
     */
    public function setDaughters($daughters)
    {
        $this->daughters = $daughters;

        return $this;
    }


    /**
     * Get the value of org_name
     *
     * @return  string
     */ 
    public function getOrgName()
    {
        return $this->org_name;
    }

    /**
     * Set the value of org_name
     *
     * @param  string  $org_name
     *
     * @return  self
     */ 
    public function setOrgName(string $org_name)
    {
        $this->org_name = $org_name;

        return $this;
    }
}