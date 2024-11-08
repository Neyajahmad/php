<?php
include('header.php');
checkUser();  // Function called
userArea();
include('userHeader.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to the new dashboard-specific CSS -->
    <link rel="stylesheet" type="text/css" href="dashboard-style.css">
</head>
<body>

<h2>Dashboard</h2>

<!-- Dashboard table -->
<table class="dashboard-table">
    <tr>
        <th>Today's Expense</th>
        <td><?php echo getDasboardDetails('today')?></td>
    </tr>
    <tr>
        <th>Yesterday's Expense</th>
        <td><?php echo getDasboardDetails('yesterday')?></td>
    </tr>
    <tr>
        <th>This Week Expense</th>
        <td><?php echo getDasboardDetails('week')?></td>
    </tr>
    <tr>
        <th>This Month Expense</th>
        <td><?php echo getDasboardDetails('month')?></td>
    </tr>
    <tr>
        <th>This Year Expense</th>
        <td><?php echo getDasboardDetails('year')?></td>
    </tr>
    <tr>
        <th>Total Expense</th>
        <td><?php echo getDasboardDetails('total')?></td>
    </tr>
</table>

<!-- Add Expense Button -->
<div class="expense-summary">
    <a href="add_expense.php" class="add-expense-btn">Add Expense</a>
</div>

<?php
include('footer.php');
?>

</body>
</html>
