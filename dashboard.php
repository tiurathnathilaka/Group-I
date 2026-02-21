<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id='$user_id' ORDER BY expense_date DESC");

$total_query = mysqli_query($conn, "SELECT SUM(amount) AS total_amount FROM expenses WHERE user_id='$user_id'");
$total_row = mysqli_fetch_assoc($total_query);
$total_amount = $total_row['total_amount'] ? $total_row['total_amount'] : 0;

$total_records = mysqli_num_rows($result);

$month_query = mysqli_query($conn, "SELECT SUM(amount) AS month_total FROM expenses WHERE user_id='$user_id' AND MONTH(expense_date)=MONTH(CURDATE()) AND YEAR(expense_date)=YEAR(CURDATE())");
$month_row = mysqli_fetch_assoc($month_query);
$month_total = $month_row['month_total'] ? $month_row['month_total'] : 0;

$today_query = mysqli_query($conn, "SELECT SUM(amount) AS today_total FROM expenses WHERE user_id='$user_id' AND DATE(expense_date) = CURDATE()");
$today_row = mysqli_fetch_assoc($today_query);
$today_total = $today_row['today_total'] ? $today_row['today_total'] : 0;
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - DEMS</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="wrapper">

    <header class="header">
        <h1>Daily Expense Management System</h1>
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($name); ?> | <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="content">
        <aside class="sidebar">
            <h2>DEMS</h2>
            <a href="dashboard.php">💠 Dashboard</a>
            <a href="add_expense.php">➕ Add Expense</a>
            <a href="reports.php">📊 Reports</a>
            <a href="profile.php">👤 Profile</a>
        </aside>

        <main class="main">
            <div class="cards">
                <div class="card">
                    <h4>Total Records</h4>
                    <p><?php echo $total_records; ?></p>
                </div>
                <div class="card">
                    <h4>Total Expenses</h4>
                    <p>Rs. <?php echo $total_amount; ?></p>
                </div>
                <div class="card">
                    <h4>Monthly Expenses</h4>
                    <p>Rs. <?php echo $month_total; ?></p>
                </div>
                <div class="card">
                    <h4>Today's Expenses</h4>
                    <p>Rs. <?php echo $today_total; ?></p>
                </div>
            </div>

            <div class="table-section">
                <h3>Your Expenses</h3>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr <?php if($row['expense_date'] == date('Y-m-d')) echo 'class="today-row"'; ?>>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td>Rs. <?php echo $row['amount']; ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo $row['expense_date']; ?></td>
                        <td>
                            <a class="edit-btn" href="edit_expense.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a class="delete-btn" href="delete_expense.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this expense?')">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </main>
    </div> 
<footer class="footer">
        &copy 2026 Daily Expense Management System | Developed by DEMS Team
</footer>

</div>
</body>
</html>