<?php
/*
    author: jdiscipi
    description: class to abstract db conn and enforce principle of least privilege
*/
class DataBase {
    public $pdo = '';

    // DEBUG will print server info about db conn to the page (for any include)
    const DB_DEBUG = FALSE;

    // Construct a db obj
    public function __construct($dataBaseUser, $whichDataBasePassword, $dataBaseName) {
        $this->pdo = null;

        $path = 'lib/';

        // May implement this feature of an admin route but likely not enough time,
        // this will be here just in case
        if (substr(BASE_PATH, -6) == 'admin/') {
            $path = '../' . $path;
        }

        include $path . 'pass.php';
        $DataBasePassword = '';

        // Choose whether we are reading or writing
        // This is a security feature since we use the principle of least privilege (only give write if necessary)
        switch ($whichDataBasePassword) {
            case 'r': 
                $DataBasePassword = $dbReader;
                break;
            case 'w':
                $DataBasePassword = $dbWriter;
                break;
        }

        $query = NULL;

        // DSN is a string that the PDO class uses to make a MySQL db conn
        $dsn = 'mysql:host=webdb.uvm.edu;dbname=';

        if (self::DB_DEBUG) {
            echo "<p>Try connecting with phpMyAdmin with these credentials.</p>";
            echo '<p>Username: ' . $dataBaseUser;
            echo '<p>DSN: ' . $dsn . $dataBaseName;
            echo '<p>Password: ' . $DataBasePassword;
        }

        // Attempt to connect to the db
        try {
            $this->pdo = new PDO($dsn . $dataBaseName, $dataBaseUser, $DataBasePassword);
            
            if (!$this->pdo) {
                if (self::DB_DEBUG) echo '<p>You are NOT connected to the database!</p>';
                return 0;
            }
            else {
                if (self::DB_DEBUG) echo '<p>You are connected to the database!</p>';
                return $this ->pdo;
            }
        } catch (PDOExeption $e) {
            $error_message = $e->getMessage();
            if (self::DB_DEBUG) echo "<p>An error occured while connecting: $error_message </p>";
        }
    }

    // Useful for debugging, see what the query would look like for testing purposes (does not run the query)
    function displayQuery($query, $values = '') {
        if ($values != '') {
            $needle = '?';
            $haystack = $query;
            foreach ($values as $value) {
                $pos = strpos($haystack, $needle);
                if ($pos !== false) {
                    
                    $haystack = substr_replace($haystack, '"' . $value . '"', $pos, strlen($needle));
                }
            }
            $query = $haystack;
        }
        return $query;
        
    }

    // Functionally equivalent to update and delete but I like to separate them
    // This makes the calls semantic and easy to follow
    public function insert($query, $values = '') {
        $status = false;
        $statement = $this->pdo->prepare($query);

        if (is_array($values)){
            $status = $statement->execute($values);
        } else {
            $status = $statement->execute();
        }

        return $status;
    }

    // Select works slightly different, we can leverage the return of the call to pass data to the page
    public function select($query, $values = '') {
        $statement = $this->pdo->prepare($query);

        if (is_array($values)) {
            $statement->execute($values);
        } else {
            $statement->execute();
        }

        $recordSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        return $recordSet;
    }

    public function update($query, $values = '') {
        $status = false;
        $statement = $this->pdo->prepare($query);

        if (is_array($values)){
            $status = $statement->execute($values);
        } else {
            $status = $statement->execute();
        }

        return $status;
    }

    public function delete($query, $values = '') {
        $status = false;
        $statement = $this->pdo->prepare($query);

        if (is_array($values)){
            $status = $statement->execute($values);
        } else {
            $status = $statement->execute();
        }

        return $status;
    }
}