<?php 
namespace App;

class App {
	public static function main(){
		$uri = $_SERVER['REQUEST_URI']; //Запрос, передаваемый в адресной строке
		Route\Route::getURI($uri); //Вызываем статический метод Роутинга с строкой запроса в качестве аргумента
	}
}


 ?>