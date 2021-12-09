<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CS166 Final">
    <meta name="author" content="jdiscipi">
    <title>The Matrix</title>
    <link rel="stylesheet" href="css/custom.css?version=<?php print time(); ?>" type="text/css">
    <!-- Include libraries -->
    <?php
    session_start();
    require_once('lib/constants.php');
    print '<!-- make Database connections -->';
    require_once(LIB_PATH . 'Database.php');
    $thisDatabaseReader = new Database('jdiscipi_reader', 'r', DATABASE_NAME);
    $thisDatabaseWriter = new Database('jdiscipi_writer', 'w', DATABASE_NAME);
    // use on a "field" to get the sanitized output
    function getData($field) {
        if (!isset($_POST[$field])) {
            $data = "";
        }
        else {
            $data = trim($_POST[$field]);
            $data = htmlspecialchars($data);
        }
        return $data;
    }

    function generateHash($salt, $password) {
        $hashed = hash("sha256", $salt . $password);
        $stored = $salt . $hashed;
        return [$hashed, $stored];
    }

    function validatePassword($password, &$validationErrors) {
        $valid = TRUE;
        $passwordLength = strlen($password);
        if($passwordLength < 8 || $passwordLength > 25) {
            $validationErrors[] = "<p>Password must be 8-25 characters long... Your password has length: $passwordLength</p>";
            $valid = FALSE;
        }

        $oneNumber = FALSE;
        $oneLower = FALSE;
        $oneUpper = FALSE;
        $oneSpecial = FALSE;
        $validSpecialChars = ['!', '@', '#', '$', '^', '&', '*', '(', ')', '-', '+', ','];
        $chars = str_split($password);
        foreach($chars as $char) {
            if(IntlChar::islower($char)) {
                $oneLower = TRUE;
            }
            if(IntlChar::isupper($char)) {
                $oneUpper = TRUE;
            }
            if(is_numeric($char)) {
                $oneNumber = TRUE;
            }
            if(in_array($char, $validSpecialChars)) {
                $oneSpecial = TRUE;
            }
        }

        if(!$oneNumber) {
            $validationErrors[] = "<p>Your password must contain at least 1 number 0-9</p>";
            $valid = FALSE;
        }
        if(!$oneLower) {
            $validationErrors[] = "<p>Your password must contain at least 1 lowercase letter</p>";
            $valid = FALSE;

        }
        if(!$oneUpper) {
            $validationErrors[] = "<p>Your password must contain at least 1 uppercase letter</p>";
            $valid = FALSE;

        }
        if(!$oneSpecial) {
            $validationErrors[] = "<p>Your password must contain at least one character from the following set [!, @, #, $, ^, &, *, (, ), -, +, ',']</p>";
            $valid = FALSE;
        }

        return $valid;
    }
    ?>
</head>

<?php
print '<body>';
print '<!-- Start Of Body -->';
print PHP_EOL;
include 'header.php';
print PHP_EOL;
?>
