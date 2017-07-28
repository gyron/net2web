<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Card
 * @package Gyron\Net2Web\Object
 */
class Card {

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
	 * @return Card
	 */
	public function fromXml( \SimpleXMLElement $oXml ): Card {
		$this->aData = array(
			'userid' => (string)($oXml->UserID),
			'cardnumber' => (string)($oXml->CardNumber),
			'lostcard' => (string)($oXml->LostCard),
			'cardtypeid' => (string)($oXml->CardTypeID)
		);
		return $this;
	}
}