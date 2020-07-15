<?php 
namespace App\Route;


class Route{
	private static $uri; //Переменная с переданным адресом из адресной строки
	private static $api_routes = [
		'/api/webhook'=>['ApiObject'=>'WebHookApi','method'=>'postMessages'],
		'/api/messages'=>['ApiObject'=>'MessagesApi','method'=>'getMessages']
	]; //Массив с возможными в приложении API адресами.


	public static function getURI($uri){
		self::$uri = $uri; 
		if(self::checkApiRoute()){
			self::getApiRoute();
		}
		

	}

	private static function checkApiRoute(){ //Проверка, является ли маршрут в адресной строке попыткой обращения к API приложения.
		$uri_check = explode('/',self::$uri);
		$uri_check = $uri_check[1];
		return ($uri_check ==='api') ? true : false;
	}

	private static function getApiRoute(){ //Проверка, существует ли внутри массива с возможными url адресами маршрут из адресной строки, переданный в качестве аргумента в публичную статическую функции getURI
		if(isset(self::$api_routes[self::$uri])){ // Если ключ в массиве совпадает с аргументом из адресной строки
			$ApiObject = 'App\\API\\ApiObjects\\'.self::$api_routes[self::$uri]['ApiObject']; //Создаем строку, соответствующую значению ключа ApiObject подмассива роута, который совпал с аргументом адресной строки
			$ApiObject = new $ApiObject; //Создаем из этой строки объект
			$ApiObject->{self::$api_routes[self::$uri]['method']}(); //Вызываем метод, соответствующий значению ключа method подмассива роута, который совпал с агрументом адресной строки
		}
		else{
			return 0;
		}

	}





}





 ?>