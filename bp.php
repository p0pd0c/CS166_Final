<?php 
include 'top.php';
include 'nav.php';

// User authenticated?
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

// Get username and access level
$username = $_SESSION["username"];
$user = ($thisDatabaseReader->select("SELECT fldUsername, fldLevel FROM tblUser WHERE fldUsername = ?", [$username]))[0];

$level = $user["fldLevel"];

// filter by access level
if($level !== "engineer" && $level !== "admin") {
    print "<p>You must be an engineer to access this page. Go back to the <a href='index.php'>home page</a>.</p>";
    die();
}
?>

<main>
    <h2>Engineering Department</h2>
    <p>Building the matrix is a multistep process</p>
    <ul>
        <li>Neo: We need guns. Lots of guns.</li>
        <li>Neo: I know you're out there. I can feel you now. I know that you're afraid... you're afraid of us. You're afraid of change. I don't know the future. I didn't come here to tell you how this is going to end. I came here to tell you how it's going to begin. I'm going to hang up this phone, and then I'm going to show these people what you don't want them to see. I'm going to show them a world without you. A world without rules and controls, without borders or boundaries. A world where anything is possible. Where we go from there is a choice I leave to you..</li>
        <li>Neo: Woah!</li>
        <li>Switch: Listen to me, Coppertop. We don't have time for 20 Questions.</li>
        <li>Neo: Am I dead? Morpheus: Far from it.</li>
        <li>Tank: How? Morpheus: He is the one!</li>
        <li>Morpheus: What you know you can't explain, but you feel it. You've felt it your entire life, that there's something wrong with the world. You don't know what it is, but it's there, like a splinter in your mind, driving you mad.</li>
    </ul>
</main>

<?php 
include 'footer.php'
?>