<?php

$oNet2Encryption = new \Gyron\Net2Web\Encryption( '1234567890123456', \Gyron\Net2Web\Encryption::OpenSSL );
$oNet2Session = new \Gyron\Net2Web\Session( 'USERID', 'PASSWORD', 'NET2 SERVER IP', '7070', $oNet2Encryption );
$oNet2Client = new \Gyron\Net2Web\Client( $oNet2Session );
