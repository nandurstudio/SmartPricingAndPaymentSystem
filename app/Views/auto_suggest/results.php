<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestions</title>
</head>

<body>
    <h1>Suggestions</h1>
    <ul>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $result): ?>
                <li><?= htmlspecialchars($result, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No suggestions found.</li>
        <?php endif; ?>
    </ul>

</body>

</html>