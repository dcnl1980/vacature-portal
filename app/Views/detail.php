<?php
/**
 * Vacature detail view
 * 
 * Toont de details van een vacature en het sollicitatieformulier
 * 
 * @author: Chris van Steenbergen
 */

// Zet de paginatitel
$pageTitle = $vacature ? htmlspecialchars($vacature['titel']) . ' - ' . htmlspecialchars($vacature['bedrijf']) : 'Vacature niet gevonden';

// Include de header
include_once __DIR__ . '/components/header.php';
?>

<?php if (!$vacature): ?>
    <div class="vacature-niet-gevonden">
        <h2>Vacature niet gevonden</h2>
        <p>De opgevraagde vacature bestaat niet of is niet meer beschikbaar.</p>
        <a href="/index.php" class="btn">Terug naar overzicht</a>
    </div>
<?php else: ?>
    <section class="vacature-detail">
        <div class="vacature-header">
            <a href="/index.php" class="terug-link">&larr; Terug naar overzicht</a>
            <h2><?= htmlspecialchars($vacature['titel']) ?></h2>
            <div class="meta">
                <p class="bedrijf"><?= htmlspecialchars($vacature['bedrijf']) ?></p>
                <p class="locatie"><?= htmlspecialchars($vacature['locatie']) ?></p>
            </div>
        </div>
        
        <div class="vacature-content">
            <h3>Functieomschrijving</h3>
            <div class="beschrijving">
                <?= nl2br(htmlspecialchars($vacature['omschrijving'])) ?>
            </div>
            
            <?php if (!empty($vacature['contactpersoon'])): ?>
                <div class="contactpersoon">
                    <h3>Contactpersoon</h3>
                    <p><?= htmlspecialchars($vacature['contactpersoon']) ?></p>
                </div>
            <?php endif; ?>
            
            <button id="solliciteer-btn" class="btn btn-primary">Solliciteer</button>
        </div>
    </section>
    
    <section id="sollicitatie-formulier" class="sollicitatie-formulier" style="display: none;">
        <h3>Sollicitatieformulier</h3>
        
        <div id="form-feedback" aria-live="polite"></div>
        
        <form id="sollicitatie-form" action="/sollicitatie.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="vacature_id" value="<?= $vacature['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="form-group">
                <label for="naam">Naam *</label>
                <input type="text" id="naam" name="naam" required>
                <span class="error-message" id="naam-error"></span>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message" id="email-error"></span>
            </div>
            
            <div class="form-group">
                <label for="cv">CV (PDF of Word) *</label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                <span class="error-message" id="cv-error"></span>
                <small class="help-text">Maximaal 2MB</small>
            </div>
            
            <div class="form-group">
                <label for="motivatie">Motivatie *</label>
                <textarea id="motivatie" name="motivatie" rows="5" required></textarea>
                <span class="error-message" id="motivatie-error"></span>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Versturen</button>
                <button type="button" id="annuleer-btn" class="btn">Annuleren</button>
            </div>
        </form>
    </section>
<?php endif; ?>

<?php
// Include de footer
include_once __DIR__ . '/components/footer.php';
?> 