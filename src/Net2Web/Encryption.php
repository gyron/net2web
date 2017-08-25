<?php declare( strict_types=1 );

namespace Gyron\Net2Web;

/**
 * Class Encryption
 * @package Gyron\Net2Web
 */
class Encryption {

	const Mcrypt = 1;
	const OpenSSL = 2;

	/**
	 * @var string
	 */
	private $sKey;

	/**
	 * @var int
	 */
	private $nMethod;

	/**
	 * @param string $sKey
	 * @param int $nMethod
	 * @throws \Exception
	 */
	public function __construct( string $sKey, $nMethod = self::Mcrypt ) {
		$this->sKey = $sKey;
		$this->nMethod = $nMethod;

		if ( strlen( $sKey ) < 16 ) {
			throw new \Exception( 'Encryption key must be at least 16 characters in length' );
		}
	}

	/**
	 * @param int $nMethod
	 * @return Encryption
	 */
	public function changeEncryptionMethod( int $nMethod ): Encryption {
		$this->nMethod = $nMethod;
		return $this;
	}

	/**
	 * @param string $sData
	 * @return string
	 */
	public function encrypt( string $sData ): string {
		if ( $this->isMcrypt() ) {
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
	public function decrypt( string $sData ): string {
		$sDecodedData = base64_decode( $sData );
		if ( $this->isMcrypt() ) {
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
	 * @return bool
	 */
	public function isMcrypt(): bool {
		return ( $this->nMethod == self::Mcrypt );
	}

	/**
	 * @return string
	 */
	private function getEncryptAlgorithm(): string {
		if ( $this->isMcrypt() ) {
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
		return ( $this->isMcrypt()? $this->sKey: substr( $this->sKey, 0, 16 ) );
	}

	/**
	 * @return string|int
	 */
	private function getEncryptMode() {
		return ( $this->isMcrypt()? MCRYPT_MODE_ECB: OPENSSL_RAW_DATA );
	}
}
