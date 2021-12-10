<?php
include 'top.php';

// Only authetnicated users are allowed
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

// Get user info
$username = $_SESSION["username"];
$user = ($thisDatabaseReader->select("SELECT fldUsername, fldResetPassword FROM tblUser WHERE fldUsername = ?", [$username]))[0];

// Ensure that password is valid 
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
        // Generate salt
        $salt = bin2hex(random_bytes(40));
        // Grab the password from form data
        $newpassword = getData("newpassword");
        // Hash the password and combine the salt and new hash
        $hash = generateHash($salt, $newpassword);
        // Update password in db
        $thisDatabaseWriter->update("UPDATE tblUser SET fldPassword = ?, fldResetPassword = ? WHERE fldUsername = ?", [$hash[1], 0, $username]);
        // Prompt user to log in
        header("Location: logout.php");
        die();
    }
}

include 'nav.php';
?>

<main id="index">
    <?php
    // this will be true if an admin has pressed the password reset button in the admin tables
    // otherwise this form is not shown
    if($user["fldResetPassword"] == 1) {
        ?>
        <form action=<?php print PHP_SELF ?> method="post">
            <?php 
            // Show any errors to the screen
            foreach($validationErrors as $validationError) {
                print $validationError;
            }
            ?>
            <label for="password">New Password</label>
            <input type="password" id="password" name="newpassword">
            <input type="submit" value="Reset Password" name="btnPasswordReset">
        </form>
        <button id="generatePassword">Generate Secure Password</button>
        <?php
    }
    ?>
    <a href="logout.php">Logout</a>
    <h1>Welcome to The Matrix <?php print $username; ?></h1>
</main>

<?php
include 'footer.php';
?>
