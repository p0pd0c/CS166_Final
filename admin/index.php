<?php 
include 'top.php';

$sqlUsers = "SELECT fldUsername, fldLoginAttempts, fldLevel FROM tblUser";
$users = $thisDatabaseReader->select($sqlUsers, []);
?>

<main>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Access Level</th>
                <th>Login Attempts</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        foreach($users as $user) {
            print "<tr>";
                print "<td>";
                    print "$user[fldUsername]";
                print "</td>";
                print "<td>";
                    print "$user[fldLevel]";
                print "</td>";
                print "<td>";
                    ?>
                    <form action=<?php print PHP_SELF; ?> method="post">
                        <input type="hidden" name="username" value=<?php print $user["fldUsername"]; ?>>
                        <input type="submit" value=<?php print $user["fldLoginAttempts"] ?> name="btnResetLoginAttempts">
                    </form>
                    <?php
                print "</td>";
                print "<td>";
                    ?>
                    <form action=<?php print PHP_SELF; ?> method="post">
                        <input type="hidden" name="username" value=<?php print $user["fldUsername"]; ?>>
                        <input type="submit" value="Reset Password" name="btnResetPassword">
                    </form>
                    <form action=<?php print PHP_SELF; ?> method="post">
                        <input type="hidden" name="username" value=<?php print $user["fldUsername"]; ?>>
                        <input type="submit" value="Delete User" name="btnDeleteUser">
                    </form>
                    <form action=<?php print PHP_SELF; ?> method="post">
                        <input type="hidden" name="username" value=<?php print $user["fldUsername"]; ?>>
                        <input type="submit" value="Change Role" name="btnRoleForm">
                    </form>
                    <?php
                print "</td>";
            print "</tr>";

        }
        ?>
        </tbody>
    </table>
    <?php
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRoleForm"])) {
        ?>
        <form action=<?php print PHP_SELF ?> method="post">
            <label>Select New Role</label>
            <select size="3" name="newrole" id="newrole">
                <option value="guest">guest</option>
                <option value="accountant">accountant</option>
                <option value="engineer">engineer</option>
                <option value="manager">manager</option>
                <option value="admin">admin</option>
            </select>
            <input type="hidden" name="username" value=<?php print getData("username") ?>>
            <input type="submit" name="btnChangeUserRole" value="Change Role">
        </form>
        <?php
    }
    ?>
</main>

<?php 
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["btnResetLoginAttempts"])) {
    $sqlResetLoginAttempts = "UPDATE tblUser SET fldLoginAttempts = 0 WHERE fldUsername = ?";
    $dataResetLoginAttemtps = array();
    $dataResetLoginAttemtps[] = getData("username");
    $thisDatabaseWriter->update($sqlResetLoginAttempts, $dataResetLoginAttemtps);
    header("Location: index.php");
}

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["btnResetPassword"])) {
    $sqlResetPassword = "UPDATE tblUser SET fldResetPassword = ? WHERE fldUsername = ?";
    $dataResetPassword = array();
    $dataResetPassword[] = 1;
    $dataResetPassword[] = getData("username");
    $thisDatabaseWriter->update($sqlResetPassword, $dataResetPassword);
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnDeleteUser"])) {
    $sqlDeleteUser = "DELETE FROM tblUser WHERE fldUsername = ?";
    $dataDeleteUser = array();
    $dataDeleteUser[] = getData("username");
    $thisDatabaseWriter->delete($sqlDeleteUser, $dataDeleteUser);
    header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnChangeUserRole"])) {
    $sqlChangeUserRole = "UPDATE tblUser SET fldLevel = ? WHERE fldUsername = ?";
    $dataChangeUserRole = array();
    $dataChangeUserRole[] = getData("newrole");
    $dataChangeUserRole[] = getData("username");
    $thisDatabaseWriter->update($sqlChangeUserRole, $dataChangeUserRole);

    // When a user is made 'admin' add them to tblAdmin so they can log in to view the admin page using WebAuth (username must be a valid netId)
    if(getData("newrole") === "admin") {
        $sqlUpdateAdmins = "INSERT INTO tblAdmin VALUES (?)";
        $thisDatabaseWriter->insert($sqlUpdateAdmins, [getData("username")]);
    }
    header("Location: index.php");
}

include 'footer.php';
?>