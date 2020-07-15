<?php 
namespace App\API\ApiObjects;
use App\Includes;
use App\API\ApiModel;

class MessagesApi extends Api
{
	public function getMessages(){
		$this->setHeaders(); //Устанавливаем заголовки ответа
		$MessagesApiModel = new ApiModel\MessagesApiModel(Includes\DB::getConnection()); //Создаем объект для работы с базой данных, в качестве аргумента передаем возвращаемое значение объекта соединения с базой данных из статического метода getConnection() класса DB.
		$messages = $MessagesApiModel->queryMessages(); 
		echo json_encode($messages,JSON_UNESCAPED_UNICODE);
	}

}





 ?>