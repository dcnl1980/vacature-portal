<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Vacatureoverzicht' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Vacatureportal</h1>
            <nav>
                <ul>
                    <li><a href="/index.php">Vacatures</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <?php if (isset($alertMessage)): ?>
            <div class="alert <?= $alertType ?? 'info' ?>">
                <?= htmlspecialchars($alertMessage) ?>
            </div>
        <?php endif; ?> 