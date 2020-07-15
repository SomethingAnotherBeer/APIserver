<?php 
namespace App\Includes;


class DB
{
	private static $connection;

	public static function getConnection(){
		self::setConnection('localhost','testdatabase','root','12345qqqwww');
		return self::$connection;
	}

	private static function setConnection($hostname,$dbname,$user,$password){
		self::$connection = new \PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8",$user,$password);
		self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	}
	
}


 ?>