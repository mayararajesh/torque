<?php

/**
 * Definining constants for the required data throughout application
 * do not touch this file.
 * 
 * @author      Rajesh Mayara<rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
/**
 * AES Encrytion/Decrytion Cypher Key
 * Don not change at all except at installation time.
 */
define('AES_CYPHER_KEY',  md5('Locuz Enterprise Solutions Limited'));
/**
 * Ajax Request's Response Codes
 */
define('SUCCESS', 100);
define('INVALID_REQUEST', 101);
define('DATABASE_ERROR', 102);
define('COMMAND_ERROR', 103);
define('AUTHENTICATION_ERROR', 104);
define('INVALID_ACCESS', 105);

