# net2web
PHP client library for Net2Web


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
$oNet2Session = new Session( 'USERID', 'PASSWORD', 'NET2 SERVER IP', '7070' );
$oNet2Client = new Client( $oNet2Session );
```
  
This will get you connected immediately, however it will create a new Net2Web session for **every request**.  
It is recommended that you use `$oNet2Session->getSessionId()`, and cache this somewhere (for up to 2 to 4 hours). This can then be reused by passing the Session ID back into the Session instance: `new Session( ..., ..., ..., '7070', $sessionId );`
