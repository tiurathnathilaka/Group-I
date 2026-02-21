<?php
session_start();
include 'config.php';

$error = "";

if (isset($_POST['login'])) {

   
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    
    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address!";
    }
    else {

        
        $email = mysqli_real_escape_string($conn, $email);

        
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($query);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>


<html>
<head>
    <title>Login - DEMS</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <h2>Daily Expense Management System</h2>

        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <p class="register">
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

    </div>
</div>

</body>
</html>
