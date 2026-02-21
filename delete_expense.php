<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    
    if(!is_numeric($expense_id) || $expense_id <= 0){
        
        header("Location: dashboard.php");
        exit;
    }

   
    $query = "DELETE FROM expenses WHERE id='$expense_id' AND user_id='$user_id'";
    
    if(mysqli_query($conn, $query)){
        header("Location: dashboard.php"); 
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    
    header("Location: dashboard.php");
    exit;
}
?>
