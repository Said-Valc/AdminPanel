<?php 
require_once "functions.php"; //подключаем файл functions.php в самом начале
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>


	
	<div class="container">
	
	  <div class="row">
		<div class="col">
		<?if($_SESSION['auth'] === false || !isset($_SESSION['auth'])){ //вызываем форму авторизации если не существует сессии ?>
			<?include('auth.php');?>
		<? }else{ ?>
			<?include('table_users.php'); // если авторизация сделана, открываем таблицу?>
		<? } ?>
		</div>
		
		
	  </div>
	</div>
   

  
    
  </body>
</html>