MyCore is a OpenSource GPL PHP Based Core for WebSites
======================================================

The idea of MyCore is to make easy calls like Check Logged user, Login User and Manage Databases.

In This file i will be explaining how to use MyCore in your WebSite.

We are still in Alpha, so, more iten's will be implemented within time,

How-to Set-Up:
1. Copy the mycore.php script inside your HTML/PHP root dir, on most linux system is /var/www or /srv/http
2. Edit the mycore.php and change the text "Whatever you want here" inside the SAL to the test for SALLING the passwords.
3. Test to see if MyCore.php it's working, for that, see bellow the How-to Test section.
4. type include_once('mycore.php'); on the top of every PHP file that you want to use MyCore.

ATENTION: The MyCore don't work alone, if you try to call the script by itself you just get a blank page.

How-to test:
Create a blank php page and put the follow content to test if MyCore and PHP are working:
<?php
  include_once('mycore.php');
  itswork();
?>

Run this blank PHP page and see the result, possible results:

-> A page with a big "It's working, i think..." text and PHP/MyCore version:
  That means that everything should work just fine.

-> A page with the code that you just wrote on the PHP file:
  The PHP isn't working, check you installation and settings.
 
-> A page with a bunch of PHP errors or a blank page:
  Something is wrong with your PHP or your Apache settings/version.
  
-> Mssing PHP Version:
  Your PHP/Apache version is too old or is corrupted, install a new version of PHP/Apache.
  
Assuming that everything is working,

ATENTION: Remember to add include_once('mycore.php'); at the top of every php file that you want to run MyCore.

All the MyCore functions require that you log into a DataBase, to do that, you create an Object with the class database:

$mydatabase = new database(construction args);

The costruction args that you need to put are (in order):

-> $database: The database name, first arg, obrigatory.
-> $password: The password to the user that will log to access that database, obrigatory.
-> $login: The login to acess the database, it's not obrigatory, if you leave blank it will assume that the login is 'root'.
-> $host: The location (web/ip location) of the database, if you leave blank it will assume that the database is 'localhost'.

Example (complete):
$database = new database("test","12345","administrator","192.168.1.58");

Example (simple):
$database = new database("test","12345"); // It's will assume that the user is 'root' and the host is 'localhost'

OBS: Since MyCore use PDO to access the DataBase you can access several db's at the same time, creating different objects.

Inside this object you have several methods to modify the database, are they:
-> newtable:
  Create a new table inside the database, the args are:
  					  -> $name: the name of the table
						  -> $types: an array with the values and the types for the database, similar when you use in pure SQL.
						  -> $pk: The primary key for that table.
Example:   $values = array("id INT NOT NULL AUTO_INCREMENT","login VARCHAR(255)","password VARCHAR(41)");
	   $database->newtable("users",$values,"id");
	   

-> deltabel
  Delete a table from the database, the arg is:
					     -> $name: the game of the table
Example: $database->deltable("users");

-> newvalue
  Insert a value in a table, the args are:
					-> $table: the name of the table
					-> $keys: an array with the keys that you want to add
					-> $values: an array (in the same order as $keys) with the values that you want to add
Example: $keys = array("login","password");
	 $values = array("admin","12345");
	 $database->newvalue("users",$keys,$values);
	 
	 
-> delvalue
  Remove a value from a table, the args are:
				      -> $table: the name of the table
				      -> $parameter: the column (key) that you want to use the search
				      -> $search: the value to search
Example: $database->delvalue("users","login","admin");

-> showvalues
  Show values from a table in a matrix, the args are:
					      -> $table: the name of the table.
					      -> $parameter: the column (key) that you want to use the search (optional).
					      -> $search: the value to search (optional).
OBS: You can just type the name of the table, without the parameters to get a matrix with the entire table.
Example 1: $admin = $database->showvalues("users","login","admin");
Example 2: $allusers = $database->showvalues("users");

-> altervalues
  Alter values from lines on a table, the args are:
						-> $table: the name of the table.
						-> $parameter: the parameter (key) to search the values.
						-> $search: the values to search.
						-> $keys: a array with the keys that will be changed.
						-> $altered: the altered values (in the same pattern as $keys).
Example: $keys = array("password");
	 $values = array("12345");
	 $database->altervalues("users","login","admin",$keys,$values);

-> disconnect
  Disconnect from the database, but DON'T distroy the object, this funcion have no args.
  Example: $database->disconnect();
  
OBS: Key = Column.

There also some functions outside the Database to login control, you can use them, they are:
OBS: MyCore login control is kinda "special", so it's won't work mixed with other login systems.
-> is_logged
  Check if a user is logged by checking the $_SESSION, and return the username of the logged user, in case of there's nobody logged it's return NULL.
  Example: $return = is_logged();  

-> login
  Make login with an user, using sha1 encriptation and the SAL that you typed at the begginning of the file, it's has the following args:
																      -> $user -> The username that you're trying to login.
																      -> $password -> The password (unencripted) that you're trying to login.
																      -> $database -> The database class object from the database where the logins are registered.
																      -> $table -> The table where's the logins are registered (optional).
																      -> $loginfield -> the Key (column) where the logins are registered (optional).
																      -> $passfield -> the key (column) where the passwords are registred (optional).
OBS: If you ommit the $table, $loginfield or $passfield, it will assume that has a table called 'users' with a field 'login' and other 'password'.
OBS: When you login, it's will be responded with a false if the login was fail or any problem with the database or true if the login was sucessfull, will also register the username on $_SESSION.
Example 1: $return = login("admin","12345",$database);
Example 2: $return = login("admin,"12345",$database,"logins","usernames","passphases");

-> logout
  Just logout a user, this field don't require any interaction.
  Example: logout();

There's also 3 functions that don't require any database to work:
->newtable:
  The newtable function outside a database creates a HTML Table using in base a matrix, you can even output directly the result of $database->showvalues() to the newtable function.
  Example: $array = array(array("name","password","test"),array("lucas","12345","ok"),array("pedro","54321","fail"));
	   newtable($array);
  OBS: It will print the table on the HTML, no return to any var.

-> inject:
  This function receives a text, check for SQL Injection and fix then if necessary, then, return the corrected text.
  Example: $id = inject($_GET["id"]);
  OBS: This function is incomplete yet, and cannot preddict any type of SQL Injection
  
-> itswork:
  This function show a information panel sayng that everything on the side of MyCore is working, you can use everywhere in your code.

To add a new user in the database you can use the method newvalue:
  include_once('mycore.php');
  $keys = array("login","password");
  $values = array("admin",sha1("12345".SAL)); // The login is 'admin' and the password is '12345' in this case.
  $database->newvalue("users",$keys,$values);
  
MyCore uses MySQL or MariaDB and is developed by Artur 'hoOmE' Paiva under GPLv3, a copy of GPLv3 can be found at http://www.gnu.org/licenses/gpl.html.
