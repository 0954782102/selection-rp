<?php
session_start();

require_once ("../functions.php");

global $db;
global $ucp_table_settings;


if($_POST['action'] == "change_password")
{
	$new_pass_1 = trim($_POST['new_password_1']);
	$new_pass_2 = trim($_POST['new_password_2']);
	if(!empty($new_pass_1) && !empty($new_pass_2))
	{
		if($new_pass_1 == $new_pass_2)
		{
			if(strlen($new_pass_1) >= 6 && strlen($new_pass_1) <= 32)//Допустимая длина пароля
			{
				if (preg_match("#^[aA-zZ0-9\-_]+$#",$new_pass_1)) //Проверка на запрещеные символы
				{ 
					if($ucp_settings['s_md5']) $pass = md5($new_pass_1);
					else $pass = $new_pass_1;
				
					$sql = "UPDATE `{$ucp_table_settings['table']}` SET `{$ucp_table_settings['pass']}` = :password_user WHERE `{$ucp_table_settings['name']}` = :user_name";
					$statement = $db->prepare($sql);
					$statement->bindParam (':password_user', $pass);
					$statement->bindParam (':user_name', $_SESSION['NickName']);
			
					$statement->execute();
					message('success','Успех','Вы успешно сменили пароль!
		 			Перезайдите с новым паролем','/profile/exit');

				}
				else message('warning','Ошибка','Ваш пароль содержит запрещенные символы');	 
			}
			else message('warning','Ошибка','Допустимая длина пароля от 6 до 32 символов');	
		}
		else message('warning','Ошибка','Пароли не совпадают');
	}
	else message('warning','Ошибка','Заполните все поля');
}
if($_POST['action'] == "login") 
{
	$password = trim($_POST['user_password']);
	$name = trim($_POST['user_name']);
	// $captcha_key = trim($_POST['captcha_key']);

	if(!empty($password) && !empty($name))
	{

			$sql = "SELECT `{$ucp_table_settings['pass']}` FROM `{$ucp_table_settings['table']}` WHERE `{$ucp_table_settings['name']}` = '$name' LIMIT 1";
			// message('warning','Ошибка',$sql );	
			$statement = $db->prepare($sql);
			$statement->execute ();

			if($statement->rowCount())
			{
				$data = $statement->fetch();

			 	if($ucp_settings['s_md5']) 
				{
					if($data[$ucp_table_settings['pass']] == md5($password))
				 	{
				 		session_start();
				 		$_SESSION['NickName'] = $name;
				 		$_SESSION['Password'] = $password;
				 		
				 		message('success','Успех','Вы успешно авторизировались!
				 		Сейчас вас перенаправят в Личный кабинет','/profile/'); 		
				 	}	
				 	else message('warning','Ошибка','Данные введены неверно исправте ошибку и попробуйте снова!');
				}
				else
				{
					if($data[$ucp_table_settings['pass']] == $password)
				 	{
				 		session_start();
				 		$_SESSION['NickName'] = $name;
				 		$_SESSION['Password'] = $password;
				 		
				 		message('success','Успех','Вы успешно авторизировались!
				 		Сейчас вас перенаправят в Личный кабинет','/profile/'); 		
				 	}	
				 	else message('warning','Ошибка','Данные введены неверно исправте ошибку и попробуйте снова!');
				}
			}
			else message('warning','Ошибка','Данные введены неверно');
		
			
	

	}
	else message('warning','Ошибка','Заполните все поля');
		



}


if($_POST['action'] == "roulette_check_balance")
{
	$nick = $_SESSION['NickName'];
    $sql = "SELECT `{$ucp_table_settings['donate']}`,`{$ucp_table_settings['online']}` FROM `{$ucp_table_settings['table']}` WHERE `{$ucp_table_settings['name']}` = :name_user LIMIT 1";
	$statement = $db->prepare($sql);
	$statement->bindParam (':name_user', $nick);
	$statement->execute (); 
	if($statement->rowCount())
	{
		$data = $statement->fetch();
		if($data[$ucp_table_settings['online']] == 0)
		
			if($data[$ucp_table_settings['donate']] >= $ucp_settings['s_donate_cost']) echo "success";
			else echo "cash";
			
		
		else echo "online";
		
	}
	else echo "error";	

}
if($_POST['action'] == "roulette_get_item")
{
	

	$sql = "SELECT * FROM ucp_item_roulette ";
	$statement = $db->prepare($sql);
	$statement->execute ();
	$arr = [];
	if($statement->rowCount()) 
	{
			while($logs_info=$statement->fetch())
			{ 
				 $tmp = []; // инициализируем массив $tmp
	 
				   $tmp  = array('id' => $logs_info['id'],'i_name' => $logs_info['i_name'],'i_images' => $logs_info['i_images'], 'i_change' => $logs_info['i_change']);

				   $arr[] = $tmp; // в общий массив записывается
			}
	}
	echo json_encode($arr);
}

if($_POST['action'] == "roulette_get_balance") {
	 $data = GetUserData();
	echo $data[$ucp_table_settings['donate']];
	
} 
if($_POST['action'] == "roulette_generate") {

	$nick = $_SESSION['NickName'];
	$sql = "SELECT `{$ucp_table_settings['donate']}` FROM `{$ucp_table_settings['table']}` WHERE `{$ucp_table_settings['name']}` = :name_user LIMIT 1";
	$statement = $db->prepare($sql);
	$statement->bindParam (':name_user', $nick);
	$statement->execute ();
	$data = $statement->fetch();
	$donate = $data[$ucp_table_settings['donate']]-$ucp_settings['s_donate_cost'];


	$sql = "UPDATE `{$ucp_table_settings['table']}` SET `{$ucp_table_settings['donate']}` = :donate_user WHERE `{$ucp_table_settings['name']}` = :name_user LIMIT 1";
	$statement = $db->prepare($sql);
	$statement->bindParam (':donate_user', $donate);
	$statement->bindParam (':name_user', $nick);
	$statement->execute ();


	$sql = "SELECT * FROM ucp_item_roulette ";
	$statement = $db->prepare($sql);
	$statement->execute ();
	$arrs = [];
	if($statement->rowCount()) 
	{
			while($logs_info=$statement->fetch())
			{ 
				 $tmp = []; // инициализируем массив $tmp
	 
				   $tmp  = array('id' => $logs_info['id'],
				   				'i_name' => $logs_info['i_name'],
				   				'i_category' => $logs_info['i_category'],
				   				'i_images' => $logs_info['i_images'], 
				   				'i_start_rand' => $logs_info['i_start_rand'], 
				   				'i_end_rand' => $logs_info['i_end_rand'],
				   				'i_change' => $logs_info['i_change']
				   			);

				   $arrs[] = $tmp; // в общий массив записывается
			}
	}


 function getItem($data) {
   $randArray = array();

   foreach ($data as $value) {
     for ($i = 0; $i < $value['i_change']; $i++) { 
      $randArray[] = $value['id'];
     }
   }

   return $randArray[rand(0, count($randArray) - 1)];
 }



 	$drop = getItem($arrs);
	echo $drop;
 	for ($i = 0; $i < count($arrs); $i++) { 
       if($arrs[$i]['id'] == $drop)
       {

       if($arrs[$i]['i_start_rand'] != $arrs[$i]['i_end_rand'])
       {
       	$value = mt_rand($arrs[$i]['i_start_rand'],$arrs[$i]['i_end_rand']);
       }
       else $value = $arrs[$i]['i_start_rand'];

       $data_priz = date("d.m.Y H:i");
	    $category = $arrs[$i]['i_category'];
	    $sql = "INSERT INTO `ucp_drop_roulette`(`p_number`, `p_user`, `p_data`, `p_value`, `p_id`, `p_status`) VALUES (NULL,'{$nick}', '{$data_priz}', '{$value}', '{$category}', '0')";

 	echo $sql;
	$result = $db->query($sql, PDO::FETCH_ASSOC);


 	break;
 }

    }


    
}	
