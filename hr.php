<?php 
include 'top.php';
include 'nav.php';

if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

$username = $_SESSION["username"];
$user = ($thisDatabaseReader->select("SELECT fldUsername, fldLevel FROM tblUser WHERE fldUsername = ?", [$username]))[0];

$level = $user["fldLevel"];

if($level !== "manager" && $level !== "admin") {
    print "<p>You must be a manager to access this page. Go back to the <a href='index.php'>home page</a>.</p>";
    die();
}
?>

<main>
    <h2>HR Department</h2>
    <p>Recruiting Agents now, please send out recruiting email</p>
    <h3>Agents currently in the matrix:</h3>
    <ul>
        <li>Ash</li>
        <li>Bird</li>
        <li>Finn</li>
        <li>Fine</li>
        <li>Gray</li>
        <li>White</li>
    </ul>
</main>

<?php 
include 'footer.php'
?>