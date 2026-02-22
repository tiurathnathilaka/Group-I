<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$expense_id = $_GET['id'];


$result = mysqli_query($conn, "SELECT * FROM expenses WHERE id='$expense_id' AND user_id='$user_id'");
$expense = mysqli_fetch_assoc($result);


if(isset($_POST['update'])){
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['expense_date'];

    mysqli_query($conn, "UPDATE expenses SET title='$title', amount='$amount', category='$category', expense_date='$date' WHERE id='$expense_id' AND user_id='$user_id'");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <link rel="stylesheet" href="add_expense.css">
</head>
<body>


<div class="container">
<form method="POST">
    <h1>Edit Expense </h1>
    <label>Title</label>
    <input type="text" name="title" value="<?php echo $expense['title']; ?>" required><br><br>

    <label>Amount</label>
    <input type="number" name="amount" value="<?php echo $expense['amount']; ?>" required><br><br>

    <label>Category</label>
    <select name="category" required>
        <option value="Food" <?php if($expense['category']=='Food') echo 'selected'; ?>>Food</option>
        <option value="Transport" <?php if($expense['category']=='Transport') echo 'selected'; ?>>Transport</option>
        <option value="Shopping" <?php if($expense['category']=='Shopping') echo 'selected'; ?>>Shopping</option>
        <option value="Bills" <?php if($expense['category']=='Bills') echo 'selected'; ?>>Bills</option>
        <option value="Others" <?php if($expense['category']=='Others') echo 'selected'; ?>>Others</option>
    </select><br><br>

    <label>Date</label>
    <input type="date" name="expense_date" value="<?php echo $expense['expense_date']; ?>" required><br><br>

    <button type="submit" name="update">Update</button>
    <button><a href="dashboard.php">Cancel</a></button>
</form>

</body>
</html>