<?php declare( strict_types=1 );

namespace Gyron\Net2Web;

/**
 * Trait Request
 * @package Gyron\Net2Web
 */
trait Request {

	/**
	 * @var string
	 */
	private $sKey;

	/**
	 * @var Encryption
	 */
	private $oEncryption;

	/**
	 * @return Encryption
	 */
	public function getEncryption(): Encryption {
		return $this->oEncryption;
	}

	/**
	 * @param string $sUrl
	 * @param string $sMethod
	 * @param array $aParameters
	 * @return \SimpleXMLElement
	 * @throws \Exception
	 */
	protected function send( string $sUrl, string $sMethod, array $aParameters = array() ): \SimpleXMLElement {
		if ( !empty( $aParameters ) && empty( $this->oEncryption ) ) {
			throw new \Exception( 'Parameters supplied, but missing is encryption.' );
		}

		foreach ( $aParameters as $sName => $sValue ) {
			$aParameters[$sName] = sprintf( '%s=%s', $sName, rawurlencode( $this->oEncryption->encrypt( (string)$sValue ) ) );
		}

		$aParameters['_method'] = 'method='.$sMethod;

		$sResponse = file_get_contents(
			$sUrl
			.($this->oEncryption->isMcrypt()? '?': '?ssl=yes&' )
			.implode( '&', $aParameters )
		);

		if ( $sResponse === false ) {
			throw new \Exception( sprintf( 'Failed to obtain a valid response from %s', $sUrl ) );
		}
		$sDecryptedResponse = $this->oEncryption->decrypt( $sResponse );
		$oXmlResult = simplexml_load_string( $sDecryptedResponse );

		// todo: standard verification of XML?

		return $oXmlResult;
	}
}