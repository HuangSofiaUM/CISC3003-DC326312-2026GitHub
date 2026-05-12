<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

$email = (string) filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'available' => false, 'message' => 'Invalid email format']);
    exit;
}

require __DIR__ . '/connect.php';
$sql = 'SELECT id FROM users WHERE email = ? LIMIT 1';
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    echo json_encode(['ok' => false, 'available' => false, 'message' => 'SQL prepare failed']);
    $mysqli->close();
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$exists = $result ? $result->num_rows > 0 : false;
$stmt->close();
$mysqli->close();

echo json_encode([
    'ok' => true,
    'available' => !$exists,
    'message' => $exists ? 'Email already registered' : 'Email available',
]);
exit;
?>
