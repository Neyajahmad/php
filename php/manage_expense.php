<?php
include('header.php');
checkUser();  // Check user authentication
userArea();
$msg = "";
$category_id = "";
$item = "";
$price = "";
$details = "";
$expense_date = "";
$added_on = date('Y-m-d\TH:i'); // Set default added_on value
$label = "Add Expense";
$id = 0;  // Initialize $id to 0

// Fetch the user ID of the currently logged-in user
$added_by = $_SESSION['UID'];

// Fetch all categories for the dropdown
$category_options = [];
$category_res = mysqli_query($con, "SELECT id, name FROM category");
while ($category_row = mysqli_fetch_assoc($category_res)) {
    $category_options[] = $category_row;
}

// Check if editing an existing expense
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $label = "Edit Expense";
    $id = get_safe_value($_GET['id']);
    
    // Fetch expense by ID, including the category name and added_by field
    $res = mysqli_query($con, "SELECT expense.*, category.name AS category_name FROM expense JOIN category ON expense.category_id = category.id WHERE expense.id = $id AND expense.added_by = '$added_by'");
    
    // Check if expense is found and belongs to the logged-in user
    if(mysqli_num_rows($res) == 0) {
        redirect('expense.php');
    }
    
    $row = mysqli_fetch_assoc($res);
   
    // Populate fields if expense exists
    if ($row) {
        $category_id = $row['category_id'];
        $item = $row['item'];
        $price = $row['price'];
        $details = $row['details'];
        $expense_date = $row['expense_date'];
        $added_on = date('Y-m-d\TH:i', strtotime($row['added_on']));
    } else {
        $msg = "Expense not found or you don't have permission to edit this item.";
    }
}

if (isset($_POST['submit'])) {
    $category_id = get_safe_value($_POST['category_id']);
    $item = get_safe_value($_POST['item']);
    $price = get_safe_value($_POST['price']);
    $details = get_safe_value($_POST['details']);
    $expense_date = get_safe_value($_POST['expense_date']);
    $added_on = get_safe_value($_POST['added_on']);

    // Prevent duplicate entries of the same item within the same category
    $res = mysqli_query($con, "SELECT * FROM expense WHERE item = '$item' AND category_id = '$category_id' AND id != $id AND added_by = '$added_by'");
    if (mysqli_num_rows($res) > 0) {
        $msg = "Expense item already exists in this category.";
    } else {
        if ($id > 0) {
            // Update existing expense
            mysqli_query($con, "UPDATE expense SET category_id = '$category_id', item = '$item', price = '$price', details = '$details', expense_date = '$expense_date', added_on = '$added_on' WHERE id = $id AND added_by = '$added_by'");
        } else {
            // Insert new expense, including added_by field
            mysqli_query($con, "INSERT INTO expense (category_id, item, price, details, expense_date, added_on, added_by) VALUES ('$category_id', '$item', '$price', '$details', '$expense_date', '$added_on', '$added_by')");
        }
        
        redirect('expense.php');
    }
}

include('userHeader.php');
?>

<!-- Display Add/Edit Expense Header -->
<h2><?php echo $label; ?></h2>
<a href="expense.php">Back to Expenses</a>
<br>

<!-- Form for adding or editing expense -->
<form method="post">
    <table border="1">
        <tr>
            <td>Category</td>
            <td>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($category_options as $option): ?>
                        <option value="<?php echo $option['id']; ?>" <?php if ($option['id'] == $category_id) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($option['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Item</td>
            <td><input type="text" name="item" required value="<?php echo htmlspecialchars($item); ?>"></td>
        </tr>
        <tr>
            <td>Price</td>
            <td><input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($price); ?>"></td>
        </tr>
        <tr>
            <td>Details</td>
            <td><input type="text" name="details" value="<?php echo htmlspecialchars($details); ?>"></td>
        </tr>
        <tr>
            <td>Expense Date</td>
            <td><input type="date" name="expense_date" required value="<?php echo $expense_date ?: date('Y-m-d'); ?>"></td>
        </tr>
        <tr>
            <td>Date Added</td>
            <td><input type="datetime-local" name="added_on" required value="<?php echo $added_on; ?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>

<?php echo $msg; ?>

<?php
include('footer.php');
?>
