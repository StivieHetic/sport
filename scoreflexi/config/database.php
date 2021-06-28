<?php
if (!defined('DATABASE')) {
	define('DATABASE', '');

	define('DB_HOST','localhost');
	define('DB_NAME','score');
	define('DB_USERNAME','root');
	define('DB_PASSWORD','');

	function create_connection() {
		try {  
			$connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $connection;
		} catch(PDOExecption $e) {
			die('erreur create_connection: '. $e->getMessage());
		}
	}
}
?>
