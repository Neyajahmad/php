<?php

function prx($data){
    echo '<pre>';
    print_r($data);
    die();
}

function get_safe_value($data){
    global $con;
    if($data) {
        return mysqli_real_escape_string($con, $data);
    }
}

function redirect($link){
    ?>
    <script>
        window.location.href="<?php echo $link ?>";
    </script>
    <?php
}

function checkUser(){
    if(isset($_SESSION['UID']) && $_SESSION['UID'] != '') {
        // User is logged in, do nothing
    } else {
        redirect('index.php');
    }
}


function getDasboardDetails($type) {
    global $con;
    $today = date('Y-m-d');
    $sub_sql = "";
    $from = "";
    $to = "";

    if ($type == 'today') {
        $sub_sql = "and expense_date = '$today'";
        $from = $today;
        $to = $today;
    } elseif ($type == 'yesterday') {
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $sub_sql = "and expense_date = '$yesterday'";
        $from = $yesterday;
        $to = $yesterday;
    } elseif (in_array($type, ['week', 'month', 'year'])) {
        $from = date('Y-m-d', strtotime("-1 $type"));
        $sub_sql = "and expense_date BETWEEN '$from' AND '$today'";
        $to = $today;
    } elseif ($type == 'total') {
        // No date filter for total
        $sub_sql = "";
        $from = "";
        $to = "";
    }

    $res = mysqli_query($con, "SELECT SUM(price) AS price FROM expense where added_by='".$_SESSION['UID']."' $sub_sql");

    $row = mysqli_fetch_assoc($res);
    $p = 0;
    $link = "";
    if ($row['price'] > 0) {
        $p = $row['price'];
        if ($type == 'total') {
            $link = "&nbsp;<a href='dashboardReport.php'> Details</a>";
        } else {
            $link = "&nbsp;<a href='dashboardReport.php?from=" . $from . "&to=" . $to . "'> Details</a>";
        }
    }

    return $p . $link;
}
function adminArea()
{
    if($_SESSION['UROLE']!='Admin'){
        redirect('dashboard.php');
    }
}

function userArea()
{
    if($_SESSION['UROLE']!='User'){
        redirect('category.php');
    }
}

?>