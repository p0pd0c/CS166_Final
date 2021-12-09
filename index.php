<?php
include 'top.php';

if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

$username = $_SESSION["username"];
$user = ($thisDatabaseReader->select("SELECT fldUsername, fldResetPassword FROM tblUser WHERE fldUsername = ?", [$username]))[0];

$validationErrors = array();

function validatePasswordReset(&$validationErrors) {
    $newpassword = getData("newpassword");
    $valid = TRUE;
    $valid = validatePassword($newpassword, $validationErrors);
    return $valid;
}

// Check if the new password from the user is valid, if so... reset password and log user out, redirect to login
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnPasswordReset"])) {
    if(validatePasswordReset($validationErrors)) {
        $salt = bin2hex(random_bytes(40));
        $newpassword = getData("newpassword");
        $hash = generateHash($salt, $newpassword);
        $thisDatabaseWriter->update("UPDATE tblUser SET fldPassword = ?, fldResetPassword = ? WHERE fldUsername = ?", [$hash[1], 0, $username]);
        header("Location: logout.php");
        die();
    }
}

include 'nav.php';
?>

<main id="index">
    <?php
    if($user["fldResetPassword"] == 1) {
        ?>
        <form action=<?php print PHP_SELF ?> method="post">
            <?php 
            foreach($validationErrors as $validationError) {
                print $validationError;
            }
            ?>
            <label for="newpassword">New Password</label>
            <input type="password" id="newpassword" name="newpassword">
            <input type="submit" value="Reset Password" name="btnPasswordReset">
        </form>
        <?php
    }
    ?>
    <a href="logout.php">Logout</a>
    <h1>Welcome to The Matrix <?php print $username; ?></h1>
</main>

<?php
include 'footer.php';
?>
