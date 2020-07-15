<?php 
namespace App\API\ApiObjects;
use App\Includes;
use App\API\ApiModel;
class WebHookApi extends Api 
{
	private $messages = [];

	public function postMessages(){
		
		$this->setHeaders(); //Устанавливаем заголовки
		if(!empty($_POST['messages'])){ 
			$this->messages = json_decode($_POST['messages'],true);
			$WebHookApiModel = new ApiModel\WebHookApiModel(Includes\DB::getConnection(),$this->messages);//Создаем объект для работы с базой данных, в качестве аргумента передаем возвращаемое значение объекта соединения с базой данных из статического метода getConnection() класса DB. В качестве второго аргумента передаем полученные сообщения
			$WebHookApiModel->insertMessages();
		}
		
	}
}



 ?>