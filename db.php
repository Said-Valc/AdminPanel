<?php
require_once "config.php"; //подключаем конфиг
	$link = mysqli_connect(localhost, user, password, db); //соединяемся с базой
	
	if (mysqli_connect_errno()) {
		printf("Соединение с базой не установлено: %s\n", mysqli_connect_error()); //ошибку выводим в случаем провала соединения
		exit();
	}
	mysqli_set_charset($link, "utf8"); //устанавливаем кодировку


?>