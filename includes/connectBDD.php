<?php

$localhost = 'mysql:dbname=epitech_tp;unix_socket=/home/meng-b_l/.mysql/mysql.sock';
try
{
	$bdd = new PDO($localhost, 'root', '');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}





?>