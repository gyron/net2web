<?php declare( strict_types=1 );

namespace Gyron;

/**
 * Class Encryption
 * @package Gyron
 */
class Encryption {
	const Mcrypt = 1;
	const OpenSSL = 2;

	const Method = self::OpenSSL;
}

/**
 * Trait Request
 * @package Gyron
 */
trait Request {

	/**
	 * @param string $sUrl
	 * @param string $sMethod
	 * @param array $aParameters
	 * @return \SimpleXMLElement
	 * @throws \Exception
	 */
	protected function send( string $sUrl, string $sMethod, array $aParameters = array() ): \SimpleXMLElement {
		foreach ( $aParameters as $sName => $sValue ) {
			$aParameters[$sName] = $sName.'='.rawurlencode( $this->encrypt( (string)$sValue ) );
		}

		$aParameters['_method'] = 'method='.$sMethod;

		$sResponse = file_get_contents(
			$sUrl
			.(Encryption::Method == Encryption::Mcrypt? '?': '?ssl=yes&' )
			.implode( '&', $aParameters )
		);

		if ( $sResponse === false ) {
			throw new \Exception( sprintf( 'Failed to obtain a valid response from %s', $sUrl ) );
		}
		$sDecryptedResponse = $this->decrypt( $sResponse );
		$oXmlResult = simplexml_load_string( $sDecryptedResponse );

		// todo: standard verification of XML?

		return $oXmlResult;
	}

	/**
	 * @param string $sData
	 * @return string
	 */
	private function encrypt( string $sData ): string {
		if ( Encryption::Method == Encryption::Mcrypt ) {
			$sEncryptedData = mcrypt_encrypt(
				$this->getEncryptAlgorithm(),
				$this->getEncryptKey(),
				$sData,
				$this->getEncryptMode()
			);
		}
		else {
			$sEncryptedData = openssl_encrypt(
				$sData,
				$this->getEncryptAlgorithm(),
				$this->getEncryptKey(),
				$this->getEncryptMode()
			);
		}
		return base64_encode( $sEncryptedData );
	}

	/**
	 * @param string $sData
	 * @return string
	 */
	private function decrypt( string $sData ): string {
		$sDecodedData = base64_decode( $sData );
		if ( Encryption::Method == Encryption::Mcrypt ) {
			return mcrypt_decrypt(
				$this->getEncryptAlgorithm(),
				$this->getEncryptKey(),
				$sDecodedData,
				$this->getEncryptMode()
			);
		}
		else {
			return openssl_decrypt(
				$sDecodedData,
				$this->getEncryptAlgorithm(),
				$this->getEncryptKey(),
				$this->getEncryptMode()
			);
		}
	}

	/**
	 * @return string
	 */
	private function getEncryptAlgorithm(): string {
		if ( Encryption::Method == Encryption::Mcrypt ) {
			return MCRYPT_3DES;
		}
		else {
			return 'aes-128-ecb';
		}
	}

	/**
	 * @return string
	 */
	private function getEncryptKey(): string {
		$sKey = '12345'; // todo: DI
		return ( ( Encryption::Method == Encryption::Mcrypt )? $sKey: substr( $sKey, 0, 16 ) );
	}

	/**
	 * @return string|int
	 */
	private function getEncryptMode() {
		return ( ( Encryption::Method == Encryption::Mcrypt )? MCRYPT_MODE_ECB: OPENSSL_RAW_DATA );
	}
}