<?php
include 'config.php';

$error = "";

if (isset($_POST['register'])) {

   
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    
    if (empty($name) || empty($email) ||  empty($password)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email!";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    }
    else {

        
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered!";
        } else {

            
            $sql = "INSERT INTO users (name, email, password)
                    VALUES ('$name', '$email', '$hashedPassword')";
            mysqli_query($conn, $sql);

            
            header("Location: login.php");
            exit();
        }
    }
}
?>


<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container">
    <h2>Register</h2>

    
    <?php if (!empty($error)) { ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password"
               placeholder="Password" minlength="6" required>

        <button type="submit" name="register">Register</button>
    </form>
</div>

</body>
</html>