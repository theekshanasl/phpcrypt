<?php
error_reporting (E_ALL | E_STRICT);

include(dirname(__FILE__)."/../phpCrypt.php");
use PHP_Crypt\PHP_Crypt as PHP_Crypt;
use PHP_Crypt\Cipher as Cipher;

$text = "This is my secret message.";
$key = "^mY@TEst~Key_0123456789abcefghij";

/**
 * Cipher: ARC4
 * Mode: Stream
 */

$crypt = new PHP_Crypt($key, PHP_Crypt::CIPHER_ARC4, PHP_Crypt::MODE_STREAM);

//$iv = $crypt->createIV(); // STREAM CIPHERS DO NOT REQUIRE AN IV FOR THE STREAM MODE
$encrypt = $crypt->encrypt($text);
$decrypt = $crypt->decrypt($encrypt);

print "CIPHER: ".$crypt->cipherName()."\n";
print "MODE: ".$crypt->modeName()."\n";
print "PLAIN TEXT: $text\n";
print "PLAIN TEXT HEX: ".Cipher::string2Hex($text)."\n";
print "ENCRYPTED HEX: ".Cipher::string2Hex($encrypt)."\n";
print "DECRYPTED: $decrypt\n";
print "DECRYPTED HEX: ".Cipher::string2Hex($decrypt)."\n";
?>