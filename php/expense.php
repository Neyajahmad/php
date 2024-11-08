<?php
include('header.php');
checkUser();  //function called
userArea();
include('userHeader.php');

if(isset($_GET['type']) && $_GET['type']=='delete' && isset($_GET['id']) && $_GET['id']>0){

    $id=get_safe_value($_GET['id']);
    mysqli_query($con,"delete from expense where id=$id");
    echo "</br>data deleted</br>";
}
$res = mysqli_query($con, "SELECT expense.*, category.name FROM expense, category WHERE expense.category_id = category.id AND expense.added_by = '" . $_SESSION['UID'] . "' ORDER BY expense_date ASC");
?>
<h2>Expense</h2>
<a href="manage_expense.php">Add Expense</a>
<br>

<!-- doing CRUD operation -->
<?php
if(mysqli_num_rows($res)>0){ ?>

<table border="1">
    <tr>
        
        <td>ID</td>
        <td>Category</td>
        <td>Item</td>
        <td>Price</td>
        <td>Details</td>
        <td>Date</td>
        <td></td>
    </tr>
    <?php 
  
    while($row=mysqli_fetch_assoc($res))
    {
        ?>
        <tr>
        
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['name'];?></td>
        <td><?php echo $row['item'];?></td>
        <td><?php echo $row['price'];?></td>
        <td><?php echo $row['details'];?></td>
        <td><?php echo $row['added_on'];?></td>
        <td>
            <a href="manage_expense.php?id=<?php echo $row['id'];?>">Edit</a>&nbsp;
            <a href="?type=delete&id=<?php echo $row['id'];?>">Delete</a>
        </td>
    </tr>
    
    <?php
    } ?>
   
</table>
<?php
    }else{
        echo "NO data found";
    }
    ?>

<?php
include('footer.php');
?>
