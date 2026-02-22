<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$category_query = mysqli_query(
    $conn,
    "SELECT 
        CASE 
            WHEN category IN ('Food','Transport','Shopping','Bills') THEN category
            ELSE 'Others'
        END AS category_group,
        SUM(amount) AS total
     FROM expenses
     WHERE user_id='$user_id' 
       AND expense_date = CURDATE()
     GROUP BY category_group"
);

$categories = [];
$category_totals = [];

while ($row = mysqli_fetch_assoc($category_query)) {
    $categories[] = $row['category_group'];
    $category_totals[] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports - DEMS</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="reports.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="wrapper">

    <header class="header">
        <h1>Daily Expense Management System</h1>
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($name); ?> |
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="content">

        <div class="sidebar">
            <h2>DEMS</h2>
            <a href="dashboard.php">💠 Dashboard</a>
            <a href="add_expense.php">➕ Add Expense</a>
            <a href="reports.php" class="active">📊 Reports</a>
            <a href="profile.php">👤 Profile</a>
        </div>

        <div class="main">

            <div class="card">
                <h3 style="margin-bottom:20px; text-align:center;">
                    Today's Expenses by Category
                </h3>

                <?php if (count($categories) > 0) { ?>
                    <canvas id="categoryChart"></canvas>
                <?php } else { ?>
                    <p class="no-data">No expenses added today.</p>
                <?php } ?>

            </div>

        </div>

    </div>

</div>

<?php if (count($categories) > 0) { ?>
<script>
const ctx = document.getElementById('categoryChart').getContext('2d');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
            data: <?php echo json_encode($category_totals); ?>,
            backgroundColor: [
                '#4D96FF', 
                '#6BCB77', 
                '#FF6B6B', 
                '#FFD93D', 
                '#00C9A7'  
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#333',
                    font: {
                        size: 13
                    }
                }
            }
        }
    }
});
</script>
<?php } ?>

</body>
</html>