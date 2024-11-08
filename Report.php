<?php
include('header.php');
checkUser();  // Function to check user authentication
userArea();
include('userHeader.php');

// Initialize filters from URL parameters or form submission
$category_filter = isset($_POST['category_id']) ? $_POST['category_id'] : '';
$start_date = isset($_GET['from']) ? $_GET['from'] : (isset($_POST['start_date']) ? $_POST['start_date'] : '');
$end_date = isset($_GET['to']) ? $_GET['to'] : (isset($_POST['end_date']) ? $_POST['end_date'] : '');
$error_message = "";

// Validate the date range
if ($start_date && $end_date && strtotime($end_date) < strtotime($start_date)) {
    $error_message = "End date cannot be before the start date.";
}

// Fetch categories for the filter dropdown
$category_query = "SELECT id, name FROM category";
$category_res = mysqli_query($con, $category_query);

// Base query for report with filters
$query = "SELECT SUM(expense.price) AS price, category.name 
          FROM expense 
          JOIN category ON expense.category_id = category.id 
          WHERE expense.added_by = '" . $_SESSION['UID'] . "'";

// Apply category filter if selected
if ($category_filter) {
    $query .= " AND expense.category_id = $category_filter";
}

// Apply date range filter if dates are selected
if ($start_date && $end_date) {
    $query .= " AND expense.expense_date BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $query .= " AND expense.expense_date >= '$start_date'";
} elseif ($end_date) {
    $query .= " AND expense.expense_date <= '$end_date'";
}

// Group by category
$query .= " GROUP BY expense.category_id";

$res = mysqli_query($con, $query);

// Check if no data found
$no_data_message = (mysqli_num_rows($res) == 0) ? "No data found for the selected filters." : "";
?>

<h2>Reports</h2>

<!-- Error message for invalid date range -->
<?php if ($error_message): ?>
    <div style="color: red;"><?php echo $error_message; ?></div>
<?php endif; ?>

<!-- Category and Date Range filter form -->
<form method="post">
    <label for="category_id">Filter by Category: </label>
    <select name="category_id" id="category_id">
        <option value="">Select Category</option>
        <?php
        while ($category_row = mysqli_fetch_assoc($category_res)) {
            $selected = ($category_row['id'] == $category_filter) ? 'selected' : '';
            echo "<option value='" . $category_row['id'] . "' $selected>" . $category_row['name'] . "</option>";
        }
        ?>
    </select>
    
    <label for="start_date">Start Date: </label>
    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" max="<?php echo date('Y-m-d'); ?>">

    <label for="end_date">End Date: </label>
    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">

    <input type="submit" value="Filter">
    <input type="reset" value="Reset" onclick="window.location.href = window.location.pathname;">
</form>

<!-- Display message if no data found -->
<?php if ($no_data_message): ?>
    <div style="color: red;"><?php echo $no_data_message; ?></div>
<?php endif; ?>

<!-- Displaying the report table -->
<?php if (mysqli_num_rows($res) > 0): ?>
<table border="1">
    <tr>
        <th>Category</th>
        <th>Price</th>
    </tr>
    <?php 
    $final_price = 0; // Initialize final price variable
    while ($row = mysqli_fetch_assoc($res)) {
        $final_price += $row['price'];  // Sum up the price
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['price']); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <th>Total</th>
        <th><?php echo $final_price; ?></th>
    </tr>
</table>
<?php endif; ?>

<?php
include('footer.php');
?>
