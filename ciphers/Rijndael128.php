<?php
/*
 * Author: Ryan Gilfether
 * URL: http://www.gilfether.com/phpCrypt
 * Date: April 3, 2013
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

namespace PHP_CRYPT;
require_once(dirname(__FILE__)."/Rijndael.php");
require_once(dirname(__FILE__)."/../phpCrypt.php");


/**
 * Implement Rijndael with a 128 bit data block
 * Key sizes can be 128, 192, or 256
 * References used to implement this cipher:
 * http://www.net-security.org/dl/articles/AESbyExample.pdf
 *
 * @author Ryan Gilfether
 * @link http://www.gilfether.com/phpcrypt
 * @copyright 2013 Ryan Gilfether
 */
class Cipher_Rijndael_128 extends Cipher_Rijndael
{
	/** @type integer BITS_BLOCK The size of the block, in bits */
	const BITS_BLOCK = 128;

	//const BITS_KEY = 0;


	/**
	 * Constructor
	 * Sets the key used for encryption. Also sets the requied block size
	 * This should only be used when calling this class directly, for classes
	 * that extend this class, they should call __construct1()
	 *
	 * @param string $key string containing the user supplied encryption key
	 * @return void
	 */
	public function __construct($key)
	{
		// Set up the key
		parent::__construct(PHP_Crypt::CIPHER_RIJNDAEL_128, $key);

		// required block size in bits
		$this->bitSize(self::BITS_BLOCK);

		// expand the key now that we know the key size, and the bit size
		$this->expandKey();
	}


	/**
	 * Constructor, used only by classes that extend this class
	 *
	 * @param string $cipher_name The pre-defined cipher name of the child class
	 * @param string $key The key used for encryption/decryption
	 * @param integer $req_key_len The required key length, in bits
	 * @return void
	 */
	protected function __construct1($cipher_name, $key, $req_key_len)
	{
		parent::__construct($cipher_name, $key, $req_key_len);

		// required block size in bits
		$this->bitSize(self::BITS_BLOCK);

		// expand the key now that we know the key size, and the bit size
		$this->expandKey();
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
}