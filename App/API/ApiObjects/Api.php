<?php 
namespace App\API\ApiObjects;

abstract class Api{

	protected function setHeaders(){ // Функция установки заголовков для ответа
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
	}
}



 ?>