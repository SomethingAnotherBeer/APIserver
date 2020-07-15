<?php 
namespace App\API\ApiModel;

class WebHookApiModel extends MainModel
{	private $messages; 
	public function __construct($connection,$messages){ 
		parent::__construct($connection);
		$this->messages = $messages;
	}

	public function insertMessages(){
		$flag = true; //Флаг используем для идентификации будущего ответа.Если true- ответ хороший, в противном случае - ответ содержит информацию о ошибке
		$added = 0; //Переменная-счетчик, отображает количество добавленных в бд записей
		$this->prepareMessages();
		try{
			$this->connection->beginTransaction(); //Начинаем транзакцию. Выполнится либо весь код для каждой итерации цикла, либо не выполнится ничего;
		foreach($this->messages as $message){ 
				if($this->getAuthorParams($message['phone'])!=false){ //Если автор существует, выполним следующий код
					$author_params = $this->getAuthorParams($message['phone']); //Записываем параметры автора из таблицы authors по номеру телефона
					$author_id = $author_params['id'];
					$date = date("Y-m-d H:i:s"); //Дата сообщения
					$messages_count = intval($author_params['message_count']);
					$this->connection->exec("INSERT INTO messages (author_id,datetime,content,is_deleted) VALUES ('$author_id','$date', '{$message['message']}', 0)"); //Добавляем сообщение в таблицу messages
					$messages_count++;//Инкреминтируем количество сообщений для данного автора
					if($author_params['datetime_first_message']==null){ // Если у автора только 1 сообщение, необходимо обновить поля первого и последнего сообщения
						$date_first_message = $author_params['datetime_last_message'];
						$date_last_message = date("Y-m-d H:i:s");
						$this->connection->exec("UPDATE authors SET datetime_first_message = '$date_first_message', datetime_last_message = '$date_last_message', message_count = '$messages_count' WHERE id = '$author_id'");//Датой первого сообщения становится запись из колонки datetime_last_message, а датой последнего сообщения - текущая дата
					}
					else{ // Если у автора уже более чем одного сообщения
						$date_last_message = date("Y-m-d H:i:s");
						$this->connection->exec("UPDATE authors SET datetime_last_message = '$date_last_message', message_count = '$messages_count' WHERE id = '$author_id'"); //Просто обновляем поля
					}
					
					$added++; //Инкреминтируем переменную-счетчик для будущего ответа

				}
				else{ //Если автора не существует, выполним следующий код
					$date = date("Y-m-d H:i:s");
					$this->connection->exec("INSERT INTO authors (phone,datetime_last_message,message_count,is_banned) VALUES ({$message['phone']},'$date',1,0)"); // Добавляем автора
					$author_params = $this->getAuthorParams($message['phone']); //Извлекаем параметры автора для получения его id
					$author_id = $author_params['id'];
					$date = date("Y-m-d H:i:s"); //Дата сообщения
					$this->connection->exec("INSERT INTO messages (author_id,datetime,content,is_deleted) VALUES ('$author_id','$date','{$message['message']}', 0)"); //Добавляем сообщение в таблицу messages
					$added++;
				}
			}
			$this->connection->commit(); //Если все прошло успешно, заканчиваем транзакцию

		}
		catch(\Exception $e){
			$e->getMessage(); // В противном случае, выкидываем исключение с сообщением о ошибке и присваиваем переменной $flag значение false
			$flag = false;
		}
		if($flag){
			echo json_encode(["status"=>"ok","body"=>"added $added", "code"=>200]);
		}
		else{
			echo json_encode(["status"=>"error","body"=>"not all added","code"=>500]);
		}
		
		
	}

	private function prepareMessages(){ //Приводим сообщения из POST в премлимый вид, так как у нас изначально двойная вложенность ввиду наличия ключа messages=>, мы делаем одинарную вложенность, где каждый параметр массива предствавляет собой подмассив типа ['message'=>'value','phone'=>'value']. 
		$messages = $this->messages;
		$prepared_messages = [];
		foreach($messages as $messages_arr){
			foreach($messages_arr as $message){
				$prepared_messages[] = $message;
			}
		}
		$this->messages = $prepared_messages;
	}

	private function getAuthorParams($phone){ //Функция извлечения параметров из таблицы authors для текущего автора внутри foreach цикла внутри транзакции выше
		$author_params_query = $this->connection->query("SELECT * FROM authors WHERE phone = $phone");
		$authors_params_arr = $author_params_query->fetch(\PDO::FETCH_ASSOC);
		return (!empty($authors_params_arr)) ? $authors_params_arr : false;
	
		
		return $authors_params_arr;
	}







}



 ?>
