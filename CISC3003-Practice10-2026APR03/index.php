<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>dc326312 Huang Sofia PHP</title>
</head>
<body>
<?php
$name = 'Huang Sofia';
?>
    <h1>Huang Sofia PHP</h1>
    <p>
        The SHA256 hash of "<?php print $name; ?>" is
        <?php print hash('sha256', $name); ?>
    </p>
    <p>ASCII ART :</p>
<pre>
    *       *
    *       *
    *********
    *       *
    *       *
</pre>
    <p><a href="check.php">Click here to check the error setting</a></p>
    <p><a href="fail.php">Click here to cause a traceback</a></p>
</body>
</html>
