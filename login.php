<?php
include 'top.php';

if(isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}
?>

<?php if($_SERVER['REQUEST_METHOD'] === "GET"): ?>
<main>
    <form action=<?php print PHP_SELF ?> method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <input type="submit" name="loginSubmit">
    </form>
    <a href="register.php">Register</a>
</main>
<?php else: ?>
    <?php 
    if(isset($_POST["loginSubmit"])) {
        // Get users from db
        $sqlGetUsers = "SELECT fldUsername, fldPassword, fldLoginAttempts FROM tblUser";
        $users = $thisDatabaseReader->select($sqlGetUsers, []);

        // Check if a user exists with that username, if it does, then also verify the password
        $authorized = FALSE;
        $updated = FALSE;
        $attempts = -1;
        foreach($users as $user) {
            if($user["fldUsername"] === getData("username")) {
                // Parse out the salt from the stored hash
                $salt = substr($user["fldPassword"], 0, 80);
                $storedHash = substr($user["fldPassword"], -64);

                // Compare password and hash
                $attemptedHash = hash("sha256", $salt . getData("password"));
                if($attemptedHash === $storedHash) {
                    $authorized = TRUE;
                    if($user["fldLoginAttempts"] < 3) {
                        $_SESSION["username"] = $user["fldUsername"];
                    } else {
                        print "<p>This account has been locked out due to many unsuccessful login attempts. Please contact a system administrator!</p>";
                        print "<p>Go back to the <a href='index.php'>home page</a> and try with a different account.</p>";  
                        die();
                    }
                }

                // If the password is wrong, increment fldLoginAttempts
                if(!$authorized) {
                    $sqlIncrementAttempts = "UPDATE tblUser SET fldLoginAttempts = ? WHERE fldUsername = ?";
                    $dataIncrementAttempts = array();
                    $dataIncrementAttempts[] = (int) $user["fldLoginAttempts"] + 1;
                    $dataIncrementAttempts[] = $user["fldUsername"];
                    $updated = $thisDatabaseWriter->update($sqlIncrementAttempts, $dataIncrementAttempts);
                    // Note that user has been updated but not in local scope (only on the db), still need +1 to have accurate count
                    $attempts = $user["fldLoginAttempts"] + 1;
                }
            }
        }

        if(isset($_SESSION["username"])) {
            header("Location: index.php");
            die();
        } else {
            if($updated) {
                print "<p>Login attempt failed (attempt recorded). Username or password incorrect. Please go back to the <a href='login.php'>login page</a></p>";
                die();
            } else {
                print "<p>Login failed. Username or password incorrect. Please go back to the <a href='login.php'>login page</a></p>";
                die();
            }
        }
    } else {
        print "<p>Bad request, please go back to the <a href='index.php'>home page</a></p>";
    }
    ?>
<?php endif; ?>
<?php
include 'footer.php';
?>