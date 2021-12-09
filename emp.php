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

if($level !== "manager" && $level !== "engineer" && $level !== "accountant" && $level !== "admin") {
    print "<p>You must be an employee to access this page. Go back to the <a href='index.php'>home page</a>.</p>";
    die();
}
?>

<main>
    <h2>Benefits</h2>
    <ul>
        <li>Paid Vacation</li>
        <li>Medical Insurance</li>
        <li>Overtime Bonuses</li>
        <li>Maternity Leave</li>
    </ul>
    <h2>Disadvantages</h2>
    <ul>
        <li>You are in the matrix</li>
    </ul>
</main>

<?php
include 'footer.php';
?>