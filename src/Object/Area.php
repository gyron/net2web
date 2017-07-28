<?php declare( strict_types=1 );

namespace Gyron\Object;

/**
 * Class Area
 * @package Gyron\Object
 */
class Area {

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
	 * @return Area
	 */
	public function fromXml( \SimpleXMLElement $oXml ): Area {
		$this->aData = array(
			'areaid' => (string)($oXml->AreaID),
			'areaname' => (string)($oXml->AreaName),
			'timezoneid' => (string)($oXml->SelectedTimezoneID)
		);
		return $this;
	}
}