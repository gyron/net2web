<?php declare( strict_types=1 );

namespace Gyron\Sample;

use Gyron\Net2Web\Client;
use Gyron\Net2Web\Encryption;
use Gyron\Net2Web\PushReceiver;
use Gyron\Net2Web\Session;

/**
 * Class AccessApiFactory
 * @package Gyron\Sample
 */
class AccessApiFactory {

	/**
	 * @var string
	 */
	private $sCachePath;

	/**
	 * @param string $sCachePath
	 */
	public function __construct( string $sCachePath ) {
		$this->sCachePath = $sCachePath;
	}

	/**
	 * @param array $aConfig requires user_id, password, ip and port
	 * @return Client
	 */
	public function newClient( array $aConfig ): Client {
		return ( new Client( $this->session( $aConfig ) ) );
	}

	/**
	 * @param array $aConfig requires user_id, password, ip and port
	 * @return PushReceiver
	 */
	public function newPushReceiver( array $aConfig ): PushReceiver {
		return ( new PushReceiver( $this->session( $aConfig ) ) );
	}

	/**
	 * @param array $aConfig
	 * @return Session
	 */
	private function session( array $aConfig ): Session {
		$sCacheFile = sprintf( '%s/net2web_session.sid', rtrim( $this->sCachePath, '/' ) );

		$sSessionId = null;
		if ( is_file( $sCacheFile ) ) {
			$sSessionId = trim( file_get_contents( $sCacheFile ) );
		}

		$oNet2Encryption = new Encryption( '1234567890123456', Encryption::OpenSSL );
		$oNet2Session = new Session( $aConfig['user_id'], $aConfig['password'], $aConfig['ip'], (string)$aConfig['port'], $oNet2Encryption, $sSessionId );
		if ( $sSessionId != $oNet2Session->getSessionId() ) {
			file_put_contents( $sCacheFile, trim( $oNet2Session->getSessionId() ) );
		}
		return $oNet2Session;
	}
}