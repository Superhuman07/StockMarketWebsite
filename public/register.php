<?php 

	//configuration 
	require("../includes/config.php");

	//error_messages
	require("../includes/error_messages.php");

	$error_messages = new ErrorMessages();

	//if user reached page via GET ( as by clicking a link or via redirect)
	if ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
		//else render form 
		render("register_form.php", ["title" => "register"]);
		return;
	}

	//else if user reached page via POST (as by submitting a form via POST)
	if ( $_SERVER["REQUEST_METHOD"] === "POST" ) {

		if (empty($_POST["username"])) {
			apologize($error_messages::REGISTER_USER_EMPTY);	
		}  
		
		if (empty($_POST["password"])) {
			apologize($error_messages::REGISTER_PASSWORD_EMPTY);	
		} 
		
		if ( $_POST["password"] !== $_POST["confirmation"] ) {
			apologize($error_messages::REGISTER_PASSWORD_CONFIRM);	
		}

		$user = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
		if( !$user ) {
			apologize($error_messages::REGISTER_USER_EXISTS);
		}

		if ( !empty($_POST["username"]) ) {
			$sql = query("INSERT INTO users (username, hash, cash) VALUES(?, ?, 10000.00)", $_POST["username"], crypt($_POST["password"]));
		
			if ( $sql ) {
				$rows = query("SELECT LAST_INSERT_ID() AS id");

				if( !$rows ) {
					$id = $rows[0]["id"];
				}

				$_SESSION["id"];
				redirect("index.php");
			}
		}
	}

?>
