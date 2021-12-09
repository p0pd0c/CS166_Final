<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CS166 Final">
    <meta name="author" content="jdiscipi">
    <title>The Matrix</title>
    <link rel="stylesheet" href="../css/custom.css" type="text/css">
    <!-- Include libraries -->
    <?php
    session_start();
    require_once('../lib/constants.php');
    print '<!-- make Database connections -->';
    require_once("../" . LIB_PATH . 'Database.php');
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
    ?>
</head>

<?php
print '<body>';
print '<!-- Start Of Body -->';
print PHP_EOL;
include 'header.php';
print PHP_EOL;
include 'nav.php';
print PHP_EOL;
?>
