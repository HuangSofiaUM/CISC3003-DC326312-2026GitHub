<?php
declare(strict_types=1);
session_start();
unset($_SESSION['scenario_b_admin']);
session_regenerate_id(true);
header('Location: login.php');
exit;
?>
