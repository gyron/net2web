<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Area
 * @package Gyron\Net2Web\Object
 */
class Area extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Area
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'areaid' => (string)($oXml->AreaID),
			'areaname' => (string)($oXml->AreaName),
			'timezoneid' => (string)($oXml->SelectedTimezoneID)
		);
		return $this;
	}
}