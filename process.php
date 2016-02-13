<?php
	session_start();

	require('new-connection.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
		//call to function
		register_user($_POST); //uses the actual post!
	}
	elseif(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		login_user($_POST);
	}
	else //malicious navigation to process.php or someone is trying to log off
	{
		session_destroy();
		header('location: index.php');
	}

	function register_user($post) //just a parameter called register
	{
		///-----------begin validation----///
		$_SESSION['errors'] = array();
		if(empty($post['first_name']))
		{
			$_SESSION['errors'][] = 'First name cannot be blank';
		}
		if(empty($post['last_name']))
		{
			$_SESSION['errors'][] = 'last name cannot be blank';
		}
		if(empty($post['password']))
		{
			$_SESSION['errors'][] = 'password cannot be blank';
		}
		if($post['password'] !== $post['confirm_password'])
		{
			$_SESSION['errors'][] = "passwords must match";
		}
		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors'][] = "please use a valid email address";
		}
		///------end of validation checks -----////
		if(count($_SESSION['errors']) > 0) //if I have any errors at all!//
		{
			header('location: index.php');
			die();
		}
		else //now you need to insert data into DB//
		{
			$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at) 
			VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['password']}', '{$post['email']}', NOW(), NOW())";

			run_mysql_query($query);
			$_SESSION['success_message'] = 'User created!';
			header('location: index.php');
		}

	}

	function login_user($post) //just a parameter called post
	{
		$query = "SELECT * FROM users WHERE users.password = '{$post['password']}'
				AND users.email = '{$post['email']}'";
		$user = fetch_all($query);
		if(count($user) > 0)
		{
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['logged_in'] = TRUE;
			header('location:success.php');
		}
		else
		{
			$_SESSION['errors'][] = "Please check your credentials and try again";
			header('location: index.php');
			die();
		}
	}
?>
