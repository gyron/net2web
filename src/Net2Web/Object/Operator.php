<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Operator
 * @package Gyron\Net2Web\Object
 */
class Operator extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Operator
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'userid' => (string)($oXml->UserID),
			'surname' => (string)($oXml->Surname),
			'displayname' => (string)($oXml->DisplayName)
		);
		return $this;
	}
}