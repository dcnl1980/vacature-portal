<div class="search-form-container">
    <form id="searchForm" action="/index.php" method="GET" class="search-form">
        <div class="form-group">
            <label for="query">Wat zoek je?</label>
            <input type="text" name="query" id="query" placeholder="Functie, bedrijf of trefwoord" 
                   value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="location">Waar?</label>
            <input type="text" name="location" id="location" placeholder="Plaats of regio" 
                   value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
        </div>
        
        <button type="submit" class="btn">Zoeken</button>
    </form>
</div> 