<?php
include('config.php');
include('function.php');
unset($_SESSION['UID']);
unset($_SESSION['UNAME']);
redirect('index.php');
?>