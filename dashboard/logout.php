<?php 
session_start();
require "../connection.php";

if(session_destroy()){
	header('Location:'.SITE_URL);
}
?>