<?php 
namespace App\API\ApiModel;

abstract class MainModel
{	
	protected $connection;
	public function __construct($connection){ //Присваиваем защищенной переменной $connection объект соединения с базой данной.
		$this->connection = $connection;
	}
}



 ?>