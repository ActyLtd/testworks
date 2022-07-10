<?php

namespace Acty\Test\Model;

class OrganizationList {

    	/**
	 * @var string 
	 */
	protected $org_name;

	/**
	 * @var string
	 */
	protected $relation_type;


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
	public function setOrgName($org_name)
	{
		$this->org_name = $org_name;

		return $this;
	}

	/**
	 * Get the value of relation_type
	 *
	 * @return  string
	 */ 
	public function getRelation_type()
	{
		return $this->relation_type;
	}

	/**
	 * Set the value of relation_type
	 *
	 * @param  string  $relation_type
	 *
	 * @return  self
	 */ 
	public function setRelationType($relation_type)
	{
		$this->relation_type = $relation_type;

		return $this;
	}
}