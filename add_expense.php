<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['save'])){
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date = mysqli_real_escape_string($conn, $_POST['expense_date']);

    mysqli_query($conn, 
    "INSERT INTO expenses (user_id,title,amount,category,expense_date)
     VALUES ('$user_id','$title','$amount','$category','$date')");

    header("Location: dashboard.php");
    exit();
}
?>
<html>
<head> 
    <title>Add Expense</title>
    <link rel="stylesheet" href="add_expense.css">
</head>
<body>
<div class="container">
    <form method="POST">
        <h1>Add Expense</h1>

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Amount</label>
        <input type="number" name="amount" required>

        <label>Category</label>
        <select name="category" required>
            <option value="Food">Food</option>
            <option value="Transport">Transport</option>
            <option value="Shopping">Shopping</option>
            <option value="Bills">Bills</option>
            <option value="Others">Others</option>
        </select>

        <label>Date</label>
        <input type="date" name="expense_date" required>

        <button type="submit" name="save">Save Expense</button>
        <button type="button" onclick="window.location='dashboard.php'">Cancel</button>
    </form>
</div>
</body>
</html>
