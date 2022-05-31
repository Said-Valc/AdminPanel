<?php
	require_once "db.php"; //подключам файл базы данных
	
if(isset($_POST['clickAuth'])){ //если сделан клик авторизации, запускаем проверку
	$login = trim(strip_tags($_POST['login'])); //принимаем логин
	$password = trim(strip_tags($_POST['password'])); //принимаем пароль
	if($login === ADMIN && $password === PASSWORD){ //проверяем логин и пароль
		$_SESSION['auth'] = true;
		successRedirect('Авторизация прошла успешно'); //если авторизация прошла успешно, то вызываем функцию редиректа и записи в сесии сообщения
	}
}elseif(isset($_POST['clickAddUser'])){ // если нажата кнопка добавления юзера
	// принимаем все данные и проверяем их и обрабатываем
	$fio = mysqli_real_escape_string($link,trim(strip_tags($_POST['fio'])));
	$dob = mysqli_real_escape_string($link,trim(strip_tags($_POST['dob'])));
	$phone = mysqli_real_escape_string($link,trim(strip_tags($_POST['phone'])));
	$login = mysqli_real_escape_string($link,trim(strip_tags($_POST['login'])));
	$email = mysqli_real_escape_string($link,trim(strip_tags($_POST['email'])));
	$password = hashPassword(mysqli_real_escape_string($link,trim(strip_tags($_POST['password'])))); //делаем из пароля хэш
	$regdate = time(); // получаем текущее время
	$img = '';// создаем переменную чтоб была
	if($_FILES['file']['name'] != ''){// если файл был загружен то загружаем файл
		$img = uploadImage($_FILES['file']);
	}
	
	if(addUser($fio,$dob,$phone,$login,$email,$password, $img, $regdate)){ // отправляем данные для добавления юзера
		successRedirect('Юзер добавлен'); // если успешно добавили то делаем редирект 
	}else{
		errorRedirect('Произошла ошибка при добавлении юзера');  //если возникла ошибка то выдаем сообщение и делаем редирект
	}	
}elseif(isset($_POST['clickEditUser'])){ // если была нажата кнопка обнавления пользователя или редактирования
		// получаем данные
	$fio = mysqli_real_escape_string($link,trim(strip_tags($_POST['fio'])));
	$dob = mysqli_real_escape_string($link,trim(strip_tags($_POST['dob'])));
	$phone = mysqli_real_escape_string($link,trim(strip_tags($_POST['phone'])));
	$login = mysqli_real_escape_string($link,trim(strip_tags($_POST['login'])));
	$email = mysqli_real_escape_string($link,trim(strip_tags($_POST['email'])));
	$oldImg = $_POST['oldImg']; // получаем название старой картинки, чтоб если была добавленна новая, удалить старую 
	$id = $_POST['id']; // получаем id обнавляемого юзера
	$img = $_POST['oldImg']; // тут мы вместо обновляемого файла пишем старый файл, а если был добавлен новый то потом перепишем
	if($_FILES['file']['name'] != ''){
		$img = uploadImage($_FILES['file']); // тут переписываем, логика в том чтоб всегда в img было корректное значение
		if(is_file(DIR_IMG.$oldImg)){ // после того как добавили то проверяем на существование файла и удаляем
			unlink(DIR_IMG.$oldImg);//удаляем
		}
		
	}
	
	if(editUser($id, $fio,$dob,$phone,$login,$email,$password, $img)){// тут отправляем данные для редактирования
		successRedirect('Юзер обнавлен');//выдаем сообщение об успехе
		
	}else{
		errorRedirect('Произошла ошибка при добавлении юзера'); // выдаем сообщение об провале редактирования
	}	
}

if($_GET['view'] == 'editUser'){ // если мы переключились на редактирование юзера
	$id = $_GET['id'];
	if($id){
		$editData = selectUserOnID($_GET['id']); // то получаем все данные об этом юзере
	}
}elseif($_GET['view'] == 'deleteUser'){ // если мы нажали на удаление юзера
	$id = $_GET['id'];
	if($id){
		$deleteUser = deleteUserOnID($_GET['id']); //отправляем id Для удаления
		if($deleteUser){
			successRedirect('Юзер успешно удален'); // удаляем и возврощаем сообщение об успехе
		}else{
			errorRedirect('Произошла ошибка при удалении юзера'); // выдаем сообщение об провале
		}
	}
}

selectUsers();
function selectUsers(){ //выбираем всех юзеров чтоб вывести в таблице
	global $link;
	$query = "SELECT * FROM `xyz_users`";
	$result = mysqli_query($link, $query);
	if($result) {
		$data = [];
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	return false;
}

function selectUserOnID($id){ //выбираем лишь одного юзера по id 
	global $link;
	$query = "SELECT * FROM `xyz_users` WHERE `id` = $id";
	$result = mysqli_query($link, $query);
	if($result) {
		$data = mysqli_fetch_assoc($result);
		return $data;
	}
	return false;
}

function addUser($fio, $dob, $phone, $login, $email, $password, $img = null, $regdate){ // добавляем юзера
	global $link;
	$query = "INSERT INTO `xyz_users`(`fio`, `dob`, `phone`, `login`, `email`, `password`, `img`, `regdate`) 
				VALUES 
			('$fio','$dob','$phone','$login','$email','$password','$img',$regdate)";
	$result = mysqli_query($link, $query);
	if($result) return true;
	return false;
}

function editUser($id, $fio, $dob, $phone, $login, $email, $password, $img = null){ //редактируем юзера
	global $link;
	$query = "UPDATE `xyz_users` SET `fio`='$fio',`dob`= '$dob',`phone`='$phone',`login`='$login',
					       `email`='$email',`password`='$password',`img`='$img' WHERE `id` = $id";
	$result = mysqli_query($link, $query);
	if($result) return true;
	return false;
}

function deleteUserOnID($id){ // удаляем юзера
	global $link;
	$query = "DELETE FROM `xyz_users` WHERE `id` = $id";
	$result = mysqli_query($link, $query);
	if($result) return true;
	return false;
}



function hashPassword($password){ //хэшируем пароль для надежности
	$md5 = md5(SECRET.$password);
	return $md5;
}

 function uploadImage($file){ // функция для загрузки картинки
		$tmp = $file['tmp_name'];
		$formats = array('jpg', 'gif', 'png'); // форматы допустимых картинок
		$format = @end(explode(".", $file['name']));
		if(in_array($format, $formats)){
			if(is_uploaded_file($tmp)){
				$dir = DIR_IMG;
				$img = rand(0, 999).'_'.$file['name'];
				$dir .= $img;
				if(move_uploaded_file($tmp, $dir)){
					return $img;
				}else{
					errorRedirect('Произошла ошибка при добавлении картинки');
				}
			}
		}else{
			errorRedirect('Формат картинки неподходит');
		}
	}
	
function errorRedirect($message){ //функция для редиректа и сообщение об ошибке
	$_SESSION['error'] = $message;
	header('Location: index.php');
}

function successRedirect($message){ //функция для успешного сообщение и редиректа
	$_SESSION['success'] = $message;
	header('Location: index.php');
}



?>