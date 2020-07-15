<?php 

spl_autoload_register(function($classname){
	$classname = str_replace('\\','/',$classname);
	if(file_exists("$classname.php")){
		require_once "$classname.php";
	}
	else{
		throw new Exception('Не удалось найти класс');
	}
})



 ?>