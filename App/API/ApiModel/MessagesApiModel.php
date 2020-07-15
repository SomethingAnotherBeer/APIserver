<?php 
namespace App\API\ApiModel;

class MessagesApiModel extends MainModel
{	
	public function queryMessages(){
		return $this->fetchMessages($this->selectMessages());
	}
	private function selectMessages(){
		return $query = $this->connection->query("SELECT messages.id,author_id,datetime,content,datetime_first_message,datetime_last_message,message_count FROM messages INNER JOIN authors ON author_id = authors.id AND authors.is_banned = 0");
	}
	private function fetchMessages($query){
		$messages_arr = [];
		while($row = $query->fetch(\PDO::FETCH_ASSOC)){
			$messages_arr[] = $row;
		}
		return $messages_arr;
	}
}




 ?>