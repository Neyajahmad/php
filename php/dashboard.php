<?php
include('header.php');
checkUser();  //function called
userArea();
include('userHeader.php');
?>
<h2>Dashboard</h2>
<table>
    <tr>
        <td>Today's Expense</td>
        <td>
            <?php echo getDasboardDetails('today')?>
        </td>
    </tr>
    <tr>
        <td>Yesterdays's Expense</td>
        <td>
        <?php echo getDasboardDetails('yesterday')?>
        </td>
    </tr>
    <tr>
        <td>This Week Expense</td>
        <td>
        <?php echo getDasboardDetails('week')?>
        </td>
    </tr>
    <tr>
        <td>This Month Expense</td>
        <td>
        <?php echo getDasboardDetails('month')?>
        </td>
    </tr>
    <tr>
        <td>This Year Expense</td>
        <td>
        <?php echo getDasboardDetails('year')?>
        </td>
    </tr>
    <tr>
        <td>Total Expense</td>
        <td>
        <?php echo getDasboardDetails('total')?>
        </td>
    </tr>
</table>
<?php
include('footer.php');
?>
