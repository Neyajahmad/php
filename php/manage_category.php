<?php
include('header.php');
checkUser();  //function called
adminArea();
$msg = "";
$category = "";
$label = "Add";
$id = 0;  // Initialize $id to 0

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $label = "Edit";
    $id = get_safe_value($_GET['id']);
    
    // Fix SQL query to fetch the category by id
    $res = mysqli_query($con, "SELECT * FROM category WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    
    // Check if the category exists
    if ($row) {
        $category = $row['name'];
    } else {
        $msg = "Category not found.";
    }
}

if (isset($_POST['submit'])) {
    $name = get_safe_value($_POST['name']);

    // Fix query to avoid conflicts with the same category name
    $res = mysqli_query($con, "SELECT * FROM category WHERE name = '$name' AND id != $id");
    if (mysqli_num_rows($res) > 0) {
        $msg = "Category already exists";
    } else {
        if ($id > 0) {
            // Update category
            mysqli_query($con, "UPDATE category SET name = '$name' WHERE id = $id");
        } else {
            // Insert new category
            mysqli_query($con, "INSERT INTO category (name) VALUES ('$name')");
        }
        
        // Redirect after successful submission
        redirect('category.php');
    }
}

include('userHeader.php');
?>

<h2><?php echo $label ?> category</h2>
<a href="category.php">Back</a>
<br>

<form method="post">
    <table>
        <tr>
            <td>Category</td>
            <td><input type="text" name="name" required value="<?php echo htmlspecialchars($category) ?>"></td>
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
