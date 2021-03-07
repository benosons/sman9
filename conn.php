<?php


	$servername = "localhost";
	$username 	= "sippkpid";
	$password 	= "81A;.rXl3fBhP4";

	$connection = new mysqli($servername, $username, $password);

    if($connection) {
       echo 'connected';
    } else {
        echo 'there has been an error connecting';
        echo $host;
        echo $username;
        echo $password;
        echo $dbname;
    }
?>
