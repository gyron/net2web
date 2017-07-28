<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class CardType
 * @package Gyron\Net2Web\Object
 */
class CardType extends AbstractObject {

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return CardType
	 */
	public function fromXml( \SimpleXMLElement $oXml ) {
		$this->aData = array(
			'cardtypeid' => (string)($oXml->CardTypeID),
			'name' => (string)($oXml->Name)
		);
		return $this;
	}
}