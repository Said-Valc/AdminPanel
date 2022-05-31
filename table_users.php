<a href='?view=addUser'>Добавить юзера</a>

	<?if($_GET['view'] == 'addUser'){ //если мы добавляем пользователя то выводим все что в if ?>
		<form action="" method="POST" enctype='multipart/form-data'>
		  <div class="form-group">
			<input type="text" class="form-control" name="fio" id="fio" placeholder="ФИО">
		  </div>
		  <div class="form-group">
			<input type="date" class="form-control" id="dob" name="dob" value="2018-07-22" min="1970-01-01" max="2021-12-31">
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control" name="phone" id="phone" placeholder="Телефон">
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control" name="login" id="login" placeholder="Логин">
		  </div>
		  <div class="form-group">
			<input type="email" class="form-control" name="email" id="email" placeholder="email">
		  </div>
		  <div class="form-group">
			<input type="password" class="form-control" name="password" id="password" placeholder="Password">
		  </div>
		  <div class="form-group">
			<input type='file' class="form-control" name='file' id='image' />
		  </div>
		 
		  <button type="submit" name="clickAddUser" class="btn btn-primary">Добавить пользователя</button>
		</form>
	<?}elseif($_GET['view'] == 'editUser'){ // если мы редактируем пользователя, то выводим все что в if?>
		<form action="" method="POST" enctype='multipart/form-data'>
		  <input type='hidden' name='id' value="<?=$editData['id']?>" />
		  <div class="form-group">
			<input type="text" class="form-control" value="<?=$editData['fio']?>" name="fio" id="fio" placeholder="ФИО">
		  </div>
		  <div class="form-group">
			<input type="date" class="form-control" value="<?=$editData['dob']?>" id="dob" name="dob" value="2018-07-22" min="1970-01-01" max="2021-12-31">
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control" value="<?=$editData['phone']?>" name="phone" id="phone" placeholder="Телефон">
		  </div>
		  <div class="form-group">
			<input type="text" class="form-control" value="<?=$editData['login']?>" name="login" id="login" placeholder="Логин">
		  </div>
		  <div class="form-group">
			<input type="email" class="form-control" value="<?=$editData['email']?>" name="email" id="email" placeholder="email">
		  </div>
		  <input type='hidden' name='oldImg' value="<?=$editData['img']?>" />
		  <div class="form-group">
			<input type='file' class="form-control" name='file' id='image' />
		  </div>
		 
		  <button type="submit" name="clickEditUser" class="btn btn-primary">Редактировать</button>
		</form>
	<?}else{?>
	<? $users = selectUsers(); //выбираем всех пользователей?>
	<?=isset($_SESSION['success'])? '<div class="alert alert-success" role="alert">'.$_SESSION['success'].'</div>':''; unset($_SESSION['success']); //если существуют сообщение об успехе, то выводим их и сразу же удаляем?> 
	<?=isset($_SESSION['error'])? '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>':''; unset($_SESSION['error']); //если существуют сообщение ошибке то выводим и удаляем?>
	<table class="table">
		  <thead class="thead-dark">
			<tr>
			  <th scope="col">#</th>
			  <th scope="col">ФИО</th>
			  <th scope="col">Дата рождения</th>
			  <th scope="col">Телефон</th>
			  <th scope="col">Логин</th>
			  <th scope="col">Email</th>
			  <th scope="col">img</th>
			  <th scope="col">regdate</th>
			  <th scope="col">Настройки</th>
			</tr>
		  </thead>
		  <tbody>
			<?php foreach($users as $user){//всех юзеров прогояем по циклу и выводим?>
				<tr>
				  <th scope="row"><?=$user['id']?></th>
				  <td><?=$user['fio']?></td>
				  <td><?=$user['dob']?></td>
				  <td><?=$user['phone']?></td>
				  <td><?=$user['login']?></td>
				  <td><?=$user['email']?></td>
				  <td><img width='100' src="<?=DIR_IMG.$user['img']?>" /></td>
				  <td><?=date('Y-m-d H:i:s',$user['regdate'])?></td>
				  <td><a href="?view=editUser&id=<?=$user['id']?>">Редактировать</a>/<a style='color:red' href="?view=deleteUser&id=<?=$user['id']?>">Удалить</td>
				</tr>
			<? } ?>
			
			
		  </tbody>
		</table>
	<?}?>

