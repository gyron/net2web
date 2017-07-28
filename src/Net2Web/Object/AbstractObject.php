<?php declare( strict_types=1 );

namespace Gyron\Net2Web\Object;

/**
 * Class AbstractObject
 * @package Gyron\Net2Web\Object
 */
abstract class AbstractObject {

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
	 * @param string $sKey
	 * @return mixed
	 */
	public function __get( $sKey ) {
		if ( isset( $this->aData[$sKey] ) ) {
			return $this->aData[$sKey];
		}
		// todo: consider exception
		return null;
	}

	/**
	 * @param string $sKey
	 * @param mixed $mValue
	 */
	public function __set( $sKey, $mValue ) {
		if ( isset( $this->aData[$sKey] ) ) {
			$this->aData[$sKey] = $mValue;
		}
		// todo: consider exception
	}

	/**
	 * @param \SimpleXMLElement $oXml
	 * @return object
	 */
	abstract public function fromXml( \SimpleXMLElement $oXml );

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->aData;
	}
}