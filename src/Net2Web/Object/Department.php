<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Department
 * @package Gyron\Net2Web\Object
 */
class Department {

	/**
	 * @var array
	 */
	protected $aData = array();

	/**
	 * @param \SimpleXMLElement $oXml
	 */
	public function __construct( ?\SimpleXMLElement $oXml = null ) {
		if ( $oXml ) {
			$this->fromXml( $oXml );
		}
	}

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Department
	 */
	public function fromXml( \SimpleXMLElement $oXml ): Department {
		$this->aData = array(
			'departmentid' => (string)($oXml->DepartmentID),
      		'name' => (string)($oXml->Name)
		);
		return $this;
	}
}