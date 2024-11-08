<?php
include('header.php');
checkUser();  // Ensure user is logged in
adminArea();  // Ensure admin access
$msg = "";
$username = "";
$password = "";
$role = 'user';  // Default role is 'user'
$label = "Add";
$id = 0;  // Initialize $id to 0

// If editing an existing user, fetch the user details by id
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $label = "Edit";
    $id = get_safe_value($_GET['id']);
    
    // Query to fetch the user by id
    $res = mysqli_query($con, "SELECT * FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    
    // Check if the user exists
    if ($row) {
        $username = $row['username'];
        $password = $row['password'];  // This will be the hashed password
        $role = $row['role'];  // Fetch the current role
    } else {
        $msg = "User not found.";
    }
}

// Handle form submission for adding/updating users
if (isset($_POST['submit'])) {
    $username = get_safe_value($_POST['username']);
    $password = get_safe_value($_POST['password']);
    $role = 'user';  // Ensure that only users are added (no admins)

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Query to check for existing username conflict
    $res = mysqli_query($con, "SELECT * FROM users WHERE username = '$username' AND id != $id");
    if (mysqli_num_rows($res) > 0) {
        $msg = "Username already exists.";
    } else {
        if ($id > 0) {
            // Update user details with the hashed password
            mysqli_query($con, "UPDATE users SET username = '$username', password = '$hashed_password', role = '$role' WHERE id = $id");
        } else {
            // Insert new user with the hashed password
            mysqli_query($con, "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')");
        }
        
        // Redirect after successful submission
        header('Location: users.php');
        exit;
    }
}

include('userHeader.php');
?>

<h2><?php echo $label; ?> User</h2>
<a href="users.php">Back</a>
<br>

<form method="post">
    <table>
        <tr>
            <td>Username</td>
            <td><input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" required value="<?php echo htmlspecialchars($password); ?>"></td>
        </tr>
        <!-- Make the role field hidden or disabled to ensure only 'user' can be added -->
        <?php if ($id == 0): // Only for adding a new user, not editing ?>
            <tr>
                <td>Role</td>
                <td>
                    <input type="text" name="role" value="user" readonly>  <!-- The role is set to 'user' for all new users -->
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>

<?php echo $msg; ?>

<?php
include('footer.php');
?>
