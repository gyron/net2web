<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Department
 * @package Gyron\Net2Web\Object
 */
class Department extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Department
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'departmentid' => (string)($oXml->DepartmentID),
      		'name' => (string)($oXml->Name)
		);
		return $this;
	}
}