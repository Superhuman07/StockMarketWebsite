<?php
require("../includes/config.php"); 
//render("buy.php", ["title" => "Buy"]);    
    // if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        
        if (empty($_POST["symbol"]) || empty($_POST["shares"]))
        {
		apologize("Enter symbol and shares");
        }
        
        if (lookup($_POST["symbol"]) === false)
        {
                apologize("Not valid symbol.");
        }
        
        if (preg_match("/^\d+$/", $_POST["shares"]) == false)
        {
                apologize("You must enter a whole, positive integer.");
        }

        $tran = 'BUY';

        $stock = lookup($_POST["symbol"]);

        $cost = $stock["price"] * $_POST["shares"];

        $cash =	query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);	
        
        // prevent buy if user's cash < total cost  
        if ($cash < $cost)
        {
            apologize("You can't afford this purchase.");
        }         
        else
	{
                         
            query("INSERT INTO tb1 (id, symbol, shares) VALUES(?, ?, ?) 
                ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)", $_SESSION["id"], $_POST["symbol"], $_POST["shares"]);
            
            // subtract total price from cash
            query("UPDATE users SET cash = cash - ? WHERE id = ?", $cost, $_SESSION["id"]);
            
            // update history
            query("INSERT INTO trans (id, tran, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], $tran, $_POST["symbol"], $_POST["shares"], $stock["price"]);
            
            redirect("/");    
        }
    }
    else
    {
        // render buy form
        render("buy.php", ["title" => "Buy"]);
    }
    
?>
