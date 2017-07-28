<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class AccessLevel
 * @package Gyron\Net2Web\Object
 */
class AccessLevel {

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
	 * @return AccessLevel
	 */
	public function fromXml( \SimpleXMLElement $oXml ): AccessLevel {
		$this->aData = array(
			'accesslevelid' => (string)($oXml->AccessLevelID),
			'name' => (string)($oXml->Name ?? $oXml->AccessLevelName)
		);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->aData;
	}
}