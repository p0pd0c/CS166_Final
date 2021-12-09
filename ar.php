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

if($level !== "accountant" && $level !== "admin") {
    print "<p>You must be an accountant to access this page. Go back to the <a href='index.php'>home page</a>.</p>";
    die();
}
?>

<main>
    <h2>Accounting Department</h2>
    <p>The matrix accounting department is full of jokers</p>
    <ul>
        <li>Q: Why did the accountant divorce the banker? A: They couldn't reconcile their differences.</li>
        <li>Q: Did you hear about the zombie CPA? A: He charges an arm and a leg.</li>
        <li>Q: How does Santa’s accountant value his sleigh? A: Net Present Value</li>
    </ul>
</main>

<?php 
include 'footer.php'
?>