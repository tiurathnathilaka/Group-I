<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
if (!$user_query || mysqli_num_rows($user_query) == 0) {
    die("User not found.");
}
$user_data = mysqli_fetch_assoc($user_query);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id != '$user_id'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "This email is already in use. Please choose another one.";
    } else {
        $update_query = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['name'] = $name;
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Failed to update profile: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - DEMS</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="sidebar">
    <h2>DEMS</h2>
    <a href="dashboard.php">💠 Dashboard</a>
    <a href="add_expense.php">➕ Add Expense</a>
    <a href="reports.php">📊 Reports</a>
    <a href="profile.php">👤 Profile</a>
</div>

<div class="main">
    <div class="profile-form">
        <h3>Edit Profile</h3>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" action="">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>

            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>