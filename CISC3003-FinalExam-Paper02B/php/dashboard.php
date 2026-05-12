<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['scenario_b_admin'])) {
    header('Location: login.php');
    exit;
}

$rows = [];
require __DIR__ . '/connect.php';
$result = $mysqli->query('SELECT id, name, email, subject, created_at FROM contact_logs ORDER BY id DESC LIMIT 10');
if ($result !== false) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <main class="container">
        <h1>Scenario B Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars((string) $_SESSION['scenario_b_admin'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><a href="logout.php">Logout</a> | <a href="index.php">Contact Form</a></p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= (int) $row['id'] ?></td>
                        <td><?= htmlspecialchars((string) $row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $row['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>CISC3003 Web Programming: Huang Sofia + dc326312 + 2026</footer>
</body>
</html>
