<?php
/**
 * Vacature overzicht view
 * 
 * Toont een lijst van vacatures met zoekmogelijkheid
 * 
 * @author: Chris van Steenbergen
 */

// Zet de paginatitel
$pageTitle = 'Vacatureoverzicht';

// Include de header
include_once __DIR__ . '/components/header.php';

// Include het zoekformulier
include_once __DIR__ . '/components/search_form.php';
?>

<section class="vacatures-lijst">
    <h2>Beschikbare Vacatures<?= !empty($_GET['query']) || !empty($_GET['location']) ? ' - Zoekresultaten' : '' ?></h2>
    
    <?php if (empty($vacatures)): ?>
        <div class="geen-resultaten">
            <p>Er zijn geen vacatures gevonden die voldoen aan je zoekcriteria.</p>
        </div>
    <?php else: ?>
        <div class="vacature-count">
            <p>Toont <?= count($vacatures) ?> vacature<?= count($vacatures) !== 1 ? 's' : '' ?></p>
        </div>
        
        <div class="vacature-items">
            <?php foreach ($vacatures as $vacature): ?>
                <div class="vacature-item">
                    <h3><?= htmlspecialchars($vacature['titel']) ?></h3>
                    <div class="vacature-info">
                        <p class="bedrijf"><?= htmlspecialchars($vacature['bedrijf']) ?></p>
                        <p class="locatie"><?= htmlspecialchars($vacature['locatie']) ?></p>
                    </div>
                    <a href="/index.php?page=detail&id=<?= $vacature['id'] ?>" class="btn">Bekijk vacature</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
// Include de footer
include_once __DIR__ . '/components/footer.php';
?> 