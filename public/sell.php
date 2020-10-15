<?php
    // add the configuration php done previously changed
    require("../includes/config.php");  
    
    // 
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        
        $tran = 'SELL';
        
        $stock = lookup($_POST["symbol"]);
        
        
        $shares = query("SELECT shares FROM tb1 WHERE id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        
        
        $value = $stock["price"] * $shares[0]["shares"];
        
       
        query("UPDATE users SET cash = cash + ? WHERE id = ?", $value, $_SESSION["id"]);
        
        query("DELETE FROM tb1 WHERE id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);        
        
        // update history
        query("INSERT INTO trans (id, transaction, symbol, shares, price) VALUES (?, ?, ?, ?, ?)", $_SESSION["id"], $tran, $_POST["symbol"], $shares[0]["shares"], $stock["price"]);
        // redirect to portfolio 
        redirect("/");
    }
    
    // if form hasn't been submitted select from tb2
    if else
    {
        
        $rows =	query("SELECT * FROM tb2 WHERE id = ?", $_SESSION["id"]);	
       
    }
// else go through table 1 anyways and render the sell form through table 1
else
{
        
        $rows =	query("SELECT * FROM tb1 WHERE id = ?", $_SESSION["id"]);	
       
        $stocks = [];
        
        foreach ($rows as $row)	
        {   
            
            $stock = $row["symbol"];
            
            
            $stocks[] = $stock;       
        }    
        // render sell form with selling stocks
        render("sell.php", ["title" => "Sell", "stocks" => $stocks]);
    }
      
?>
