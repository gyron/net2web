# net2web
PHP client library for [Net2Web](http://akostyra.net/index.php/net2web.html).

## About
Net2, by [Paxton](https://www.paxton.co.uk), is an access control systems developer and provider. Their software run on windows and does have an SDK (in .NET), however it does not have a remotely accessible API. Net2Web runs alongside Net2 and exposes ~90% of the SDK functionality as an XML web accessible API.

This library aims to simply integration with the Net2Web exposed API.

## Installation Instructions
### Composer
Via command line simply do:  
```
composer require gyron/net2web
```

Alternatively you can update the `"require": {` section in your `composer.json` with a line reading:
```
"gyron/net2web" : "master"
```
(Note: We have not yet versioned this library and so simply installing directly from master is all that is available for the moment).

## Usage
### Quick Start
Simply copy and paste the following code, and adjust the values:
```
$oNet2Encryption = new \Gyron\Net2Web\Encryption( '1234567890123456', \Gyron\Net2Web\Encryption::OpenSSL );
$oNet2Session = new \Gyron\Net2Web\Session( 'USERID', 'PASSWORD', 'NET2 SERVER IP', '7070', $oNet2Encryption );
$oNet2Client = new \Gyron\Net2Web\Client( $oNet2Session );
```
  
This will get you connected immediately, however it will create a new Net2Web session for **every request**.  
It is recommended that you use `$oNet2Session->getSessionId()`, and cache this somewhere (for up to 2 to 4 hours). This can then be reused by passing the Session ID back into the Session instance:  
`new Session( ..., ..., ..., ..., $oEncryption $sessionId );`

### Advanced Implementation
Following is a sample implementation of the library in the form of a service factory which utilises caching of the session ID to local storage.
```
<?php declare( strict_types=1 );

namespace Gyron\Sample;

use Gyron\Net2Web\Client;
use Gyron\Net2Web\Encryption;
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
   * @throws \Exception
   */
  public function forConfig( array $aConfig ) {
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
    return ( new Client( oNet2Session ) );
  }
}
```
