<?php
declare(strict_types=1);

/**
 * ======================================================
 * Scenario A - Form Processing + Validation + DB Insert
 * ======================================================
 */

$errors = [];
$successMessage = '';

$fullName = '';
$email = '';
$age = '';
$bio = '';
$programme = '';
$contactMethod = '';
$skills = [];

$programmeOptions = [
    'CS' => 'Computer Science',
    'DS' => 'Data Science',
    'SE' => 'Software Engineering',
];

$contactOptions = [
    'email' => 'Email',
    'phone' => 'Phone',
    'whatsapp' => 'WhatsApp',
];

$skillOptions = [
    'php' => 'PHP',
    'mysql' => 'MySQL',
    'htmlcss' => 'HTML/CSS',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * =================================
     * Input Collection + Sanitization
     * =================================
     */
    $fullName = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_UNSAFE_RAW));
    $email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $ageInput = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT, ['options' => ['min_range' => 16, 'max_range' => 100]]);
    $bio = trim((string) filter_input(INPUT_POST, 'bio', FILTER_UNSAFE_RAW));
    $programme = (string) filter_input(INPUT_POST, 'programme', FILTER_UNSAFE_RAW);
    $contactMethod = (string) filter_input(INPUT_POST, 'contact_method', FILTER_UNSAFE_RAW);
    $skillsInput = filter_input(INPUT_POST, 'skills', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $skills = is_array($skillsInput) ? $skillsInput : [];

    /**
     * ==================
     * Data Validation
     * ==================
     */
    if ($fullName === '' || mb_strlen($fullName) < 3) {
        $errors[] = 'Full name is required and must be at least 3 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if ($ageInput === false) {
        $errors[] = 'Age must be an integer between 16 and 100.';
    } else {
        $age = (string) $ageInput;
    }

    if ($bio === '' || mb_strlen($bio) < 10) {
        $errors[] = 'Bio is required and must be at least 10 characters.';
    }

    if (!array_key_exists($programme, $programmeOptions)) {
        $errors[] = 'Please select a valid programme.';
    }

    if (!array_key_exists($contactMethod, $contactOptions)) {
        $errors[] = 'Please choose a valid contact method.';
    }

    foreach ($skills as $skill) {
        if (!array_key_exists($skill, $skillOptions)) {
            $errors[] = 'Invalid skill option detected.';
            break;
        }
    }

    if (count($skills) === 0) {
        $errors[] = 'Select at least one skill.';
    }

    /**
     * ==========================================
     * SQL Injection Prevention + Prepared Insert
     * ==========================================
     */
    if (count($errors) === 0) {
        require __DIR__ . '/db_config.php';

        $skillsCsv = implode(', ', $skills);

        $sql = 'INSERT INTO student_profiles (full_name, email, age, bio, programme, contact_method, skills) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            $errors[] = 'Failed to prepare SQL statement.';
        } else {
            $ageInt = (int) $age;
            $stmt->bind_param('ssissss', $fullName, $email, $ageInt, $bio, $programme, $contactMethod, $skillsCsv);

            if ($stmt->execute()) {
                $successMessage = 'Form submitted successfully. New student record inserted.';
                $fullName = '';
                $email = '';
                $age = '';
                $bio = '';
                $programme = '';
                $contactMethod = '';
                $skills = [];
            } else {
                $errors[] = 'Database insert failed: ' . $stmt->error;
            }

            $stmt->close();
        }

        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario A Form Project</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main class="container">
        <h1>Scenario A: Dynamic Form (PHP + MySQL)</h1>
        <p class="description">This page demonstrates HTML form best practices, validation with PHP filter functions, and secure database insertion using prepared statements.</p>

        <?php if (count($errors) > 0): ?>
            <section class="message error" aria-live="polite">
                <h2>Validation Errors</h2>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ($successMessage !== ''): ?>
            <section class="message success" aria-live="polite">
                <p><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></p>
            </section>
        <?php endif; ?>

        <form action="index.php" method="post" novalidate>
            <!-- A.02 simple text input -->
            <div class="field-group">
                <label for="full_name">Full Name</label>
                <input id="full_name" name="full_name" type="text" required minlength="3" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" required value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field-group">
                <label for="age">Age</label>
                <input id="age" name="age" type="number" min="16" max="100" required value="<?= htmlspecialchars($age, ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <!-- A.03 textarea -->
            <div class="field-group">
                <label for="bio">Short Bio</label>
                <textarea id="bio" name="bio" rows="4" required><?= htmlspecialchars($bio, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <!-- A.04 select list -->
            <div class="field-group">
                <label for="programme">Programme</label>
                <select id="programme" name="programme" required>
                    <option value="">-- Select Programme --</option>
                    <?php foreach ($programmeOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $programme === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- A.04 radio buttons -->
            <fieldset class="field-group">
                <legend>Preferred Contact Method</legend>
                <?php foreach ($contactOptions as $value => $label): ?>
                    <label class="inline-option">
                        <input type="radio" name="contact_method" value="<?= $value ?>" <?= $contactMethod === $value ? 'checked' : '' ?> required>
                        <?= $label ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>

            <!-- A.04 checkboxes -->
            <fieldset class="field-group">
                <legend>Skills</legend>
                <?php foreach ($skillOptions as $value => $label): ?>
                    <label class="inline-option">
                        <input type="checkbox" name="skills[]" value="<?= $value ?>" <?= in_array($value, $skills, true) ? 'checked' : '' ?>>
                        <?= $label ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>

            <button type="submit">Submit Form</button>
        </form>
    </main>

    <footer>
        CISC3003 Web Programming: Huang Sofia + dc326312 + 2026
    </footer>
</body>
</html>
