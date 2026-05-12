<?php
declare(strict_types=1);

/**
 * ==========================================
 * Scenario B - Database Connection
 * ==========================================
 */
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'cisc3003_exam_b';
$dbPort = 3306;

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>
