<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Card
 * @package Gyron\Net2Web\Object
 */
class Card extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return Card
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'userid' => (string)($oXml->UserID),
			'cardnumber' => (string)($oXml->CardNumber),
			'lostcard' => (string)($oXml->LostCard),
			'cardtypeid' => (string)($oXml->CardTypeID)
		);
		return $this;
	}
}