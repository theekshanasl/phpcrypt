<?php
/*
 * This is a simple example showing how to encrypt and decrypt a short
 * string using AES-256. You can change the Cipher and Mode to anything
 * supported to encrypt with different ciphers and modes
 */

error_reporting (E_ALL | E_STRICT);

include(dirname(__FILE__)."/../phpCrypt.php");
use PHP_Crypt\PHP_Crypt as PHP_Crypt;
use PHP_Crypt\Cipher as Cipher;

$text = "This is my secret message.";
$key = "^mY@TEst~Key_0123456789abcefghij"; // the key will be truncated if it's too long


/**
 * Cipher: AES 256
 * Mode: CTR (Counter)
 */

$crypt = new PHP_Crypt($key, PHP_Crypt::CIPHER_AES_256, PHP_Crypt::MODE_CTR);
$iv = $crypt->createIV();

$encrypt = $crypt->encrypt($text, $iv);
$decrypt = $crypt->decrypt($encrypt, $iv);

print "CIPHER: ".$crypt->cipherName()."\n";
print "MODE: ".$crypt->modeName()."\n";
print "PLAIN TEXT: $text\n";
print "PLAIN TEXT HEX: ".Cipher::string2Hex($text)."\n";
print "ENCRYPTED HEX: ".Cipher::string2Hex($encrypt)."\n";
print "DECRYPTED: $decrypt\n";
print "DECRYPTED HEX: ".Cipher::string2Hex($decrypt)."\n";
?>