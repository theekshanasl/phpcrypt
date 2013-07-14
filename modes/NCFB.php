<?php
/*
 * Author: Ryan Gilfether
 * URL: http://www.gilfether.com/phpCrypt
 * Date: March 29, 2013
 * Copyright (C) 2013 Ryan Gilfether
 *
 * This file is part of phpCrypt
 *
 * phpCrypt is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

namespace PHP_Crypt;
require_once(dirname(__FILE__)."/../Cipher.php");
require_once(dirname(__FILE__)."/../Mode.php");
require_once(dirname(__FILE__)."/../phpCrypt.php");


/**
 * Implements N Cipher Feedback (CFB) block cipher mode, same as CFB
 * but N = the block size requirement of the cipher
 *
 * @author Ryan Gilfether
 * @link http://www.gilfether.com/phpcrypt
 * @copyright 2013 Ryan Gilfether
 */
class Mode_NCFB extends Mode
{
	/**
	 * The constructor, Sets the cipher object that will be used for encryption
	 *
	 * @param object $cipher one of the phpCrypt encryption cipher objects
	 * @return void
	 */
	public function __construct($cipher)
	{
		parent::__construct($cipher, PHP_Crypt::MODE_NCFB);

		// our block size will be the size required by the cipher
		$this->blockSize($cipher->bitSize() / 8);

		// this works with only block Ciphers
		if($cipher->type() != Cipher::BLOCK)
			trigger_error("NCFB mode requires a block cipher", E_USER_WARNING);
	}


	/**
	 * Destructor
	 *
	 * @return void
	 */
	public function __destruct()
	{
		parent::__destruct();
	}


	/**
	 * Encrypts an the entire string $plain_text using the cipher passed
	 *
	 * @param string $text the string to be encrypted in CBC mode
	 * @return boolean True
	 */
	public function encrypt(&$text)
	{
		$blocksz = $this->blockSize();

		// first we need to pad the string so its the correct length for the cipher
		$len = strlen($text);

		// if $len is less than blockSize() this will still work, as even
		// a fraction is greater than 0
		$max1 = $len / $blocksz;
		for($i = 0; $i < $max1; ++$i)
		{
			// current position in the text
			$pos = $i * $blocksz;

			// make sure we don't extend past the length of $text
			$byte_len = $blocksz;
			if(($pos + $byte_len) > $len)
				$byte_len = ($pos + $byte_len) - $len;

			// encrypt the register
			$this->enc_register = $this->register;
			$this->cipher->encrypt($this->enc_register);

			// encrypt the block by xoring it with the encrypted register
			$block = substr($text, $pos, $byte_len);

			// xor the block
			$max2 = strlen($block);
			for($j = 0; $j < $max2; ++$j)
				$block[$j] = $block[$j] ^ $this->enc_register[$j];

			// replace the plain text block with the encrypted block
			$text = substr_replace($text, $block, $pos, $byte_len);

			// shift the register left n-bytes, append n-bytes of encrypted register
			$this->register = substr($this->register, $blocksz);
			$this->register .= $block;
		}

		return true;
	}


	/**
	 * Decrypts an the entire string $plain_text using the cipher passed
	 *
	 * @param string $text the string to be decrypted in ECB mode
	 * @return bool Returns True
	 */
	public function decrypt(&$text)
	{
		$blocksz = $this->blockSize();
		$len = strlen($text);

		// if $len is less than blockSize() this will still work, as even
		// a fraction is greater than 0
		$max1 = $len / $blocksz;
		for($i = 0; $i < $max1; ++$i)
		{
			// get the current position in text
			$pos = $i * $blocksz;

			// make sure we don't extend past the length of $text
			$byte_len = $blocksz;
			if(($pos + $byte_len) > $len)
				$byte_len = ($pos + $byte_len) - $len;

			// encrypt the register
			$this->enc_register = $this->register;
			$this->cipher->encrypt($this->enc_register);

			// shift the register left, push the encrypted byte onto the end
			$block = substr($text, $pos, $byte_len);
			$this->register = $block;

			// xor the block
			$max2 = strlen($block);
			for($j = 0; $j < $max2; ++$j)
				$block[$j] = $block[$j] ^ $this->enc_register[$j];

			// replace the encrypted block with the plain text block
			$text = substr_replace($text, $block, $pos, $byte_len);
		}

		return true;
	}


	/**
	 * This mode requires an IV
	 *
	 * @return boolean true
	 */
	public function requiresIV()
	{
		return true;
	}
}
?>