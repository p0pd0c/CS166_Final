<?php 
include 'top.php';
include 'nav.php';

// This block of code will redirect the user to the login page if they haven't authenticated during the current session
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

// If we made it here, we have the username 
// Do this on all pages that need protection
// this is duplicated on purpose... it is handy to customize access on a per page basis
$username = $_SESSION["username"];
$user = ($thisDatabaseReader->select("SELECT fldUsername, fldLevel FROM tblUser WHERE fldUsername = ?", [$username]))[0];


// Now that we have the user, grab their level
$level = $user["fldLevel"];

// Only allow relevant employees
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