<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class AccessLevel
 * @package Gyron\Net2Web\Object
 */
class AccessLevel extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return AccessLevel
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'accesslevelid' => (string)($oXml->AccessLevelID),
			'name' => (string)($oXml->Name ?? $oXml->AccessLevelName)
		);
		return $this;
	}
}