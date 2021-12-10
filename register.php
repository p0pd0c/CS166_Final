<?php
include 'top.php';

// Logged in users should not see the register page
if(isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}

// Get the form data (sanitized by getData) and validate the username and password before account creation
function validateFormData(&$validationErrors) {
    $username = getData("username");
    $password = getData("password");
    $valid = TRUE;

    $usernameLength = strlen($username);
    if($usernameLength > 12 || $usernameLength < 5) {
        $validationErrors[] = "<p>Username must be 5-12 characters long... Your username has length: $usernameLength</p>";
        $valid = FALSE;
    }
    
    // see validatePassword for details
    $valid = validatePassword($password, $validationErrors);

    return $valid;
}

$username = "";
$password = "";
$validationErrors = array();

// User submitted the registration form
if($_SERVER["REQUEST_METHOD"] === "POST") {
    // Grab form data (sanitized)
    $username = getData("username");
    $password = getData("password");

    // Salt and hash password for storage
    $salt = bin2hex(random_bytes(40));
    $hashedPassword = hash("sha256", $salt . $password);
    $storedHash = $salt . $hashedPassword;

    // Validate data
    $saveData = validateFormData($validationErrors);
    // Confirm the username is unique (required since tblUser has fldUsername as a primary key)
    $sqlUsers = "SELECT fldUsername FROM tblUser";
    $users = $thisDatabaseReader->select($sqlUsers, []);
    foreach($users as $user) {
        if($user["fldUsername"] === $username) {
            $validationErrors[] = "<p>Username must be unique</p>";
            $saveData = FALSE;
        }
    }

    if($saveData) {
        // Save data
        // saves the username, hashed password, and gives default role 'guest' to new users
        $sqlRegisterUser = "INSERT INTO tblUser VALUES (?, ?, ?, ?, ?)";
        $dataRegisterUser = array();
        $dataRegisterUser[] = $username;
        $dataRegisterUser[] = $storedHash;
        $dataRegisterUser[] = "guest";
        $dataRegisterUser[] = 0;
        $dataRegisterUser[] = 0;
        $savedData = $thisDatabaseWriter->insert($sqlRegisterUser, $dataRegisterUser);
        if(DEBUG) {
            $thisDatabaseReader->displayQuery($sqlRegisterUser, $dataRegisterUser);
        } elseif(!$savedData) {
            // Something terrible has happened
            print "<p>Registration Error. Contact System Administrator. Go back to the <a href='index.php'>home page.</a></p>";
            die();
        }

        // User is now registered
        print "<p>Account $username registered. Go back to the <a href='index.php'>home page</a>.</p>";
    }
}
?>
<main>
    <!-- Password hasher is available: https://jdiscipi.w3.uvm.edu/live/register.php?hasher&pass=<>&salt=<> REPLACE <>'s with password and salt to see how the hash is built -->
    <?php if(isset($_GET["hasher"])): ?>
    Password Hash Generator (provide query params pass and salt)
    <?php 
    // Calculate and display hash
    if(isset($_GET["pass"]) && isset($_GET["salt"])) {
        $results = generateHash($_GET["salt"], $_GET["pass"]);
        print "<p>Hashed PW: $results[0]</p>";
        print "<p>Hashed PW w/ salt: $results[1]</p>";
    }
    ?>
    <?php endif; ?>
    <form action=<?php print PHP_SELF; ?> method="post">
        <?php 
        // Show any validation errors
        foreach($validationErrors as $validationError) {
            print $validationError;
        }
        ?>
        <label for="username">Username</label>
        <input text="text" id="username" name="username" value=<?php print $username; ?>>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" value=<?php print $password; ?>>
        <input type="submit" name="registerSubmit">
    </form>
    <button id="generatePassword">Generate Secure Password</button>
    <a href="login.php">Login</a>
</main>

<?php
include 'footer.php';
?>