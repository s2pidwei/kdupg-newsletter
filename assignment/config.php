<?php
	define("SITEURL","https://kdupg-newsletter.000webhostapp.com/assignment/");
	date_default_timezone_set( "Asia/Kuala_Lumpur" );
	define("CLASS_PATH","include/Classes/");
	define("TEMPLATE_PATH","../include/Template/");

	require_once(CLASS_PATH."Account.php");
	require_once(CLASS_PATH."Article.php");
	require_once(CLASS_PATH."User.php");
	require_once(CLASS_PATH."Event.php");
	require_once(CLASS_PATH."Event_Participant.php");
	require_once(CLASS_PATH."Share.php");
	require_once(CLASS_PATH."Comment.php");
	require_once(CLASS_PATH."Announcement.php");
	require_once(CLASS_PATH."Category.php");
	require_once(CLASS_PATH."Follow.php");

	define( "DBhost", "mysql:host=localhost;dbname=id11078589_kdu_newsletter" );
	define( "DBuser", "id11078589_username" );
	define( "DBpass", "password" );
?>