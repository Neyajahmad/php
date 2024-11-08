<?php
if($_SESSION['UROLE']=='User')
{?>
<a href="dashboard.php"> Dashboard</a>&nbsp;
<a href="expense.php">expense</a>&nbsp;
<a href="Report.php">Report</a>
<?php

}else{
    ?>
    <a href="category.php">category</a>&nbsp;
    <a href="users.php">Users</a>&nbsp;
    <?php
}
?>
<a href="logout.php"> LogOut</a>&nbsp;
