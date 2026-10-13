<?php
require_once '../config.php';
unset($_SESSION['admin_id'], $_SESSION['admin_name']);
redirect('../index.php');
?>