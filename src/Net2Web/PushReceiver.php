<?php declare( strict_types=1 );

namespace Gyron\Net2Web;

use Gyron\Net2Web\Object\Event;

/**
 * Class PushReceiver
 * @package Gyron\Net2Web
 */
class PushReceiver {

	/**
	 * @var Session
	 */
	private $oSession;

	/**
	 * @param Session $oSession
	 */
	public function __construct( Session $oSession ) {
		$this->oSession = $oSession;
	}

	/**
	 * @param array $aData
	 * @return Event
	 * @throws \Exception
	 */
	public function receive( array $aData ): Event {
		if ( !isset( $aData['push'] ) ) {
			throw new \Exception( 'Failed to find "push" data.' );
		}

		if ( !isset( $aData['ssl'] ) ) {
			throw new \Exception( 'Unknown encryption method. Failed to find "ssl" in data.' );
		}

		$bOpenSSL = ( strtolower( $aData['ssl'] ) === 'true' );
		$oEncryption = $this->oSession->getEncryption();
		if ( $oEncryption->isOpenSSL() !== $bOpenSSL ) {
			throw new \Exception( 'Session encryption does not match received push encryption.' );
		}

		$oEventXml = simplexml_load_string( $oEncryption->decrypt( trim( $aData['push'] ) ) );
		if ( $oEventXml === false ) {
			throw new \Exception( "Net2Web Push data was not parseable as XML." );
		}

		if ( !isset( $oEventXml->Table->EventID ) ) {
			throw new \Exception( "Net2Web Push data was not in the expected format." );
		}

		return ( new Event( $oEventXml->Table ) );
	}
}