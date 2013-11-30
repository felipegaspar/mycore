<?php
// This is a OpenSource GPL PHP based Core for Webpages.
// It's use MySQL/MariaDB
// Made by Artur 'hoOmE' Paiva <dr.hoome@gmail.com>


    // The SAL of your passwords
    define('SAL',"Whatever you want here");


    // *** Please, don't change anything beyond this unless you know what you're doing ***

    // The Version of The software
    define('VERSION',"0.0.3 Alpha");

    // Function that prevents SQL Injection (can be inproved.)
    function inject($old) {
        $new = str_replace("<","&#60;",$old);
        $new = str_replace(">","&#62;",$new);
        return($new);
    }

    // Class to acess the database.
    class database {

        // Construct Method
        function __construct($database,$password,$login="root",$host="localhost") {
            $this->conn = new PDO("mysql:host=$host;port=3306;dbname=$database;",$login,$password);
        }

        // Create a table
        function newtable($name,$types,$pk) {
            $command = "CREATE TABLE $name (";
            foreach($types as $type) {
                $command=$command.$type.",";
            }
            $command=$command."PRIMARY KEY($pk))";
            $this->conn->exec($command);
        }

        // Delete a table
        function deltable($name) {
            $rs = $this->conn->query("SHOW TABLE LIKE `$name`");
            if(count($rs)>0) {
                $this->conn->exec("DROP TABLE $name;");
            } else {
                die('Cannot find given table '.$name);
            }
            
        }

        // Insert value in a table
        function newvalue($table,$keys,$values) {
            $command = "INSERT INTO $table (";
            $counter = 1;
            foreach($keys as $key) {
                $command = $command . $key;
                if ($counter != sizeof($keys)) {
                    $command = $command . ",";
                } else {
                    $command = $command . ")";
                }
                $counter++;
            }
            $command = $command . " VALUES (";
            $counter = 1;
            foreach($values as $value) {
                if (is_numeric($value)) {
                    $command = $command . $value;
                } else {
                    $command = $command . "'" . $value . "'";
                }
                if ($counter != sizeof($values)) {
                    $command = $command . ",";
                } else {
                    $command = $command . ")";
                }
                $counter++;
            }
            $this->conn->exec($command);
        }

        // Delete values from a table
        function delvalue($table,$parameter,$search) {
            if (!(is_numeric($search))) {
                $search = "'$search'";
            }
            $this->conn->exec("DELETE FROM $table WHERE $parameter = $search");
        }

        // Select values from a table
        function showvalues($table,$parameter=null,$search=null) {
            if ($search != null) {
                if (!(is_numeric($search))) {
                    $search = "'$search'";
                }
            }
            if ($parameter == null) {
                $result = $this->conn->query("SELECT * FROM $table");
            } else {
                $result = $this->conn->query("SELECT * FROM $table WHERE $parameter = $search");
            }
            $resultado = array();
            while($value = $result->fetch(PDO::FETCH_ASSOC)) {
                $resultado[] = $value;
            }
            return $resultado;
        }

        // Alter a value from table
        function altervalues($table,$parameter,$search,$keys,$altered) {
            $command = "UPDATE $table SET ";
            $counter = 1;
            foreach($keys as $key) {
                if (!(is_numeric($altered[$counter-1]))) {
                    $altered[$counter-1] = "'" . $altered[$counter-1] . "'";
                }
                $command = $command . "$key = " . $altered[$counter-1];
                if ($counter != sizeof($keys)) {
                    $command = $command . ",";
                }
                $counter++;
            }
            if (is_numeric($search)) {
                $command = $command . " WHERE $parameter = $search";
            } else {
                $command = $command . " WHERE $parameter = '$search'";
            }
            $this->conn->exec($command);
        }

        // Disconnect from DB
        function disconnect() {
            $this->conn = NULL;
        }

    }

    // Check if has any logged user.
    function is_logged() {
        if(isset($_SESSION["login"])) {
            return $_SESSION["login"];
        } else {
            return null;
        }
    }

    // Logout a user.
    function logout() {
        if(isset($_SESSION["login"])) {
            $_SESSION['login'] = "";
            unset($_SESSION['login']);
        }
    }

    // Login a user.
    function login($user,$password,$database,$table="users",$loginfield="login",$passfield="password") {
        $mypass = sha1($password . SAL);
        $resultado = $database->showvalues($table,$loginfield,$user);
        if ($resultado == false) {
            $return = false;
            logout();
        } else {
            if ($resultado[0][$passfield] == $mypass) {
                $return = true;
                $_SESSION['login'] = $user;
            } else {
                $return = false;
                logout();
            }
        }
        return($return);
    }

    // Create a table
    function newtable($cells) {
        foreach($cells as $row) {
            print("\t<tr>");
            foreach($row as $colms) {
                print("\t\t<td>$colms</td>");
            }
            print("\t</tr>");
        }
    }

    // Show a basic HTML if everything is working.
    function itswork() {
        echo "<h2>It's Working, i think...</h2>";
        echo "<p>MyCore Version: ".VERSION."</p>";
        echo "<p>PHP Version: ".phpversion()."</p>";
    }
?>
