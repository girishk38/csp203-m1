<?php
include '../includes/common.php';
session_start();
session_unset();
session_destroy();
header("Location: index.php");
?>
