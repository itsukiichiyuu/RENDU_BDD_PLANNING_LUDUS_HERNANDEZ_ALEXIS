<?php

	define('SERVER',"localhost");
	define('BASE',"planning_ludus");
	define('USER',"root");
	define('PASSWD',"");

	$dsn="mysql:dbname=".BASE.";host=".SERVER;
	try
	{
		$connexion=new PDO($dsn,USER,PASSWD);
	}
	catch(PDOException $e)
	{
		printf("Echec de la connection: %s\n", $e->getMessage());
		exit();
	}

?>
