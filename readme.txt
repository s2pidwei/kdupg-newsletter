UOW KDUPG SOFTWARE ENGINEERING ASSIGNMENT
CODE		: CSE3033
SEMESTER	: SEPTEMBER 2019
MEMBERS		: LAU ZIHAO 0191331
		  LAM JUN WEI 0191673
		  CHEONG ZHEN XUAN 0191975
		  OH WEI SIANG 0191843

==================================================

DESCRIPTION:
This website allows users to publish articles and like, comment or share on those articles. Events can be organized online as well.

INSTALLATION (XAMPP):
1. Navigate to xampp folder.
2. Navigate to "htdocs" folder.
3. Extract zip file here.
4. Change define("SITEURL","https://kdupg-newsletter.000webhostapp.com/assignment/"); to
    define("SITEURL","assignment/");
5. Change these
	define( "DBuser", "id11078589_username" );
	define( "DBpass", "password" );    
  to
  define( "DBuser", "root" );
	define( "DBpass", "" );    
6. Create a database named "id11078589_kdu_newsletter" and import id11078589_kdu_newsletter.sql file into phpMyAdmin
7. Done.

FAQ:
1. How do I use the website?
A: First open browser, and type in "localhost/(foldername)/php/home.php", foldername as the folder that was extracted from the zip file.

2. There are a lot of errors in the home page!
A: Navigate to the website root folder, and open config.php. In the file, change the username/password to match your database.

3. So how do i sign in, because I need admin approval after signing up?
A: A normal account requires admin approval, however if you are the admin, the login id is "000000" and the password is "kdupg_admin". Do change the password to avoid problems. Here's a normal account: id:0191234  passowrd:1234qwer

4. What is the "control panel" when I signed in as admin?
A: It is a webpage that only admins are allowed to come in. It allows changing the announcement at top, and managing users/articles/events.

5. How do I ban an account?
A: There is no ban option, if you really need to prevent a user from using the webpage, try deleting their account in the database.

6. How do I change the default icon of a profile?
A: By navigating to the website root folder, and navigate into the "img" folder, you can change the default profile icon and other images, however it is case-sensitive, so please make sure the names are correct.
