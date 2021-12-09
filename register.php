<?php
include 'top.php';

if(isset($_SESSION["username"])) {
    header("Location: index.php");
    die();
}

function validateFormData(&$validationErrors) {
    $username = getData("username");
    $password = getData("password");
    $valid = TRUE;

    $usernameLength = strlen($username);
    if($usernameLength > 12 || $usernameLength < 5) {
        $validationErrors[] = "<p>Username must be 5-12 characters long... Your username has length: $usernameLength</p>";
        $valid = FALSE;
    }
    
    $valid = validatePassword($password, $validationErrors);

    return $valid;
}

$username = "";
$password = "";
$validationErrors = array();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = getData("username");
    $password = getData("password");

    // Salt and hash password for storage
    $salt = bin2hex(random_bytes(40));
    $hashedPassword = hash("sha256", $salt . $password);
    $storedHash = $salt . $hashedPassword;

    $saveData = validateFormData($validationErrors);
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
            print "<p>Registration Error. Contact System Administrator. Go back to the <a href='index.php'>home page.</a></p>";
            die();
        }

        // User is now registered
        print "<p>Account $username registered. Go back to the <a href='index.php'>home page</a>.</p>";
    }
}
?>
<main>
    <?php if(isset($_GET["hasher"])): ?>
    Password Hash Generator (provide query params pass and salt)
    <?php 
    if(isset($_GET["pass"]) && isset($_GET["salt"])) {
        $results = generateHash($_GET["salt"], $_GET["pass"]);
        print "<p>Hashed PW: $results[0]</p>";
        print "<p>Hashed PW w/ salt: $results[1]</p>";
    }
    ?>
    <?php endif; ?>
    <form action=<?php print PHP_SELF; ?> method="post">
        <?php 
        foreach($validationErrors as $validationError) {
            print $validationError;
        }
        ?>
        <label for="username">Username</label>
        <input text="text" id="username" name="username" value=<?php print $username; ?>>
        <label for="password">Password</label>
        <input text="password" id="password" name="password" value=<?php print $password; ?>>
        <input type="submit" name="registerSubmit">
    </form>
    <a href="login.php">Login</a>
</main>

<?php
include 'footer.php';
?>