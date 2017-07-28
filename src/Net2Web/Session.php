<?php declare( strict_types=1 );

namespace Gyron\Net2Web;

/**
 * Class Session
 * @package Gyron\Net2Web
 */
class Session {

	use Request {
		send as protected sendRequest;
	}

	/**
	 * @var string
	 */
	private $sPassword;

	/**
	 * @var string
	 */
	private $sUrl;

	/**
	 * @var string
	 */
	private $sUsername;

	/**
	 * @var string
	 */
	private $sSessionId;

	/**
	 * @param string $sUsername
	 * @param string $sPassword
	 * @param string $sIp
	 * @param string $sPort
	 * @param string $sSessionId
	 * @throws \Exception
	 */
	public function __construct( string $sUsername, string $sPassword, string $sIp, string $sPort, ?string $sSessionId = null ) {
		$this->sUsername = $sUsername;
		$this->sPassword = $sPassword;
		$this->sUrl = sprintf( 'http://%s:%s/oemclient.xml', $sIp, $sPort );

		if ( $sSessionId ) {
			// test the session ID
			$this->sSessionId = $sSessionId;

			$oXmlResult = $this->send( 'serverhostname' );//serverhostname, lasterrormessage
			if ( !isset( $oXmlResult->item->value ) || strpos( (string)$oXmlResult->item->value, 'error' ) != false ) {
				// problems with the session, re-authenticate
				$this->sSessionId = null;
			}
			else {
				return;
			}
		}

		$aParameters = array(
			'userid' => $sUsername,
			'password' => $sPassword
		);
		$oXmlResult = $this->send( 'authenticateuser', $aParameters );
		$sSessionId = (string)$oXmlResult->item->value;

		if ( strpos( $sSessionId, 'error' ) != false ) {
			// todo: need to load up the Exception with additional data (i.e. the raw XML)
			throw new \Exception( 'Failed to obtain a Net2 session ID. Possibly incorrect login provided.' );
		}
		$this->sSessionId = $sSessionId;
	}

	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return $this->sSessionId;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->sUrl;
	}

	/**
	 * @param string $sMethod
	 * @param array $aParameters
	 * @return \SimpleXMLElement
	 */
	protected function send( $sMethod, array $aParameters = array() ): \SimpleXMLElement {
		if ( $this->sSessionId ) {
			$aParameters['sid'] = $this->sSessionId;
		}
		return $this->sendRequest( $this->sUrl, $sMethod, $aParameters );
	}
}