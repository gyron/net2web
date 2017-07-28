<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class Operator
 * @package Gyron\Net2Web\Object
 */
class Operator {

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
	 * @return Operator
	 */
	public function fromXml( \SimpleXMLElement $oXml ): Operator {
		$this->aData = array(
			'userid' => (string)($oXml->UserID),
			'surname' => (string)($oXml->Surname),
			'displaynmae' => (string)($oXml->DisplayName)
		);
		return $this;
	}
}