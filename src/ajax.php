<?php 

	$name = $_POST['mail'];
	$date = $_POST['date'];
	$mes = $_POST['mes'];
	
	// TODO: добавить проверку на все поля
	// TODO: добавить валидацию даты и email
	// дата не может быть в прошедшем времени
	if ($name === '') {
		$data['status'] = 'error';
		$data['text'] = 'Заполните все поля!';
	}else{
		$data['status'] = 'OK';
		$data['text'] = 'Вы молодец, не забыли заполнить все поля';
	}

	header("Content-Type: application/json");
	echo json_encode($data);
	exit;

	// TODO: 
	// Раз в день крон запускает этот php файл 
	// Идет запрос в БД. Проверка всех писем со статусом "не отправлено"
	// Если дата отправки у письма == сегодняшнему числу 
	// отправляем письма и меняем статус

 ?>