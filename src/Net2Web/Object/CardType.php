<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class CardType
 * @package Gyron\Net2Web\Object
 */
class CardType {

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
	 * @return CardType
	 */
	public function fromXml( \SimpleXMLElement $oXml ): CardType {
		$this->aData = array(
			'cardtypeid' => (string)($oXml->CardTypeID),
			'name' => (string)($oXml->Name)
		);
		return $this;
	}
}