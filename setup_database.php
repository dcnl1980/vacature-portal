<?php
/**
 * Database setup script
 * 
 * Dit script initialiseert de SQLite database en vult deze met test data.
 * 
 * @author: Chris van Steenbergen
 */

if (php_sapi_name() !== 'cli') {
    echo "Dit script moet via de command line worden uitgevoerd.";
    exit(1);
}

echo "Database setup starten...\n";

$dbPath = __DIR__ . '/data/database.sqlite';

if (!is_dir(dirname($dbPath))) {
    mkdir(dirname($dbPath), 0755, true);
    echo "Data directory aangemaakt.\n";
}

if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Bestaande database verwijderd.\n";
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Nieuwe database aangemaakt.\n";

$pdo->exec('
    CREATE TABLE vacatures (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titel TEXT NOT NULL,
        bedrijf TEXT NOT NULL,
        locatie TEXT NOT NULL,
        omschrijving TEXT,
        contactpersoon TEXT
    )
');

$pdo->exec('
    CREATE TABLE sollicitaties (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        vacature_id INTEGER,
        naam TEXT NOT NULL,
        email TEXT NOT NULL,
        cv_path TEXT NOT NULL,
        motivatie TEXT,
        datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (vacature_id) REFERENCES vacatures(id)
    )
');

echo "Tabellen aangemaakt.\n";

$bedrijven = [
    'Microsoft' => ['Amsterdam', 'Rotterdam', 'Utrecht'],
    'Google' => ['Den Haag', 'Eindhoven', 'Groningen'],
    'Apple' => ['Amersfoort', 'Nijmegen', 'Tilburg'],
    'Amazon' => ['Arnhem', 'Breda', 'Enschede'],
    'Facebook' => ['Almere', 'Haarlem', 'Zwolle'],
    'Tweakers' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Wired' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'TechCrunch' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'The Next Web' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'CNET' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Nederlandse Spoorwegen' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'KPN' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'ING' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Rabobank' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Aegon' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Nu.nl' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Volkskrant' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'NRC' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'AD' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'De Telegraaf' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'De Volkskrant' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'De Nederlander' => ['Rotterdam', 'Utrecht', 'Den Haag'],
    'Gemeente Den Haag' => ['Den Haag'],
    'Gemeente Rotterdam' => ['Rotterdam'],
    'Gemeente Utrecht' => ['Utrecht'],
    'Gemeente Amsterdam' => ['Amsterdam'],
    'Gemeente Eindhoven' => ['Eindhoven'],
    'Gemeente Groningen' => ['Groningen'],
    'Gemeente Arnhem' => ['Arnhem'],
    'Gemeente Breda' => ['Breda'],
    'Gemeente Enschede' => ['Enschede'],
    'Gemeente Almere' => ['Almere'],
    'Gemeente Haarlem' => ['Haarlem'],
    'Gemeente Zwolle' => ['Zwolle'],
    'Gemeente Nijmegen' => ['Nijmegen']
];

$functieTitels = [
    'PHP Developer',
    'Full Stack Developer',
    'Frontend Developer',
    'Backend Developer',
    'Web Developer',
    'Software Engineer',
    'DevOps Engineer',
    'UX/UI Designer',
    'Project Manager',
    'Scrum Master',
    'QA Engineer',
    'Database Administrator',
    'Security Engineer',
    'UX/UI Designer'
];

$omschrijvingsTemplates = [
    "Wij zijn op zoek naar een enthousiaste %s die ons team komt versterken. In deze functie ben je verantwoordelijk voor het ontwikkelen en onderhouden van onze software applicaties. Je werkt samen met een team van ervaren ontwikkelaars aan uitdagende projecten.\n\nWat we van je verwachten:\n- Minimaal 2 jaar ervaring in soortgelijke functie\n- Kennis van moderne ontwikkelmethoden\n- Goede communicatieve vaardigheden\n- Proactieve houding en zelfstandig kunnen werken\n\nWat wij bieden:\n- Een marktconform salaris\n- Flexibele werktijden\n- Mogelijkheid tot thuiswerken\n- Ruimte voor persoonlijke ontwikkeling\n- Een dynamisch en gezellig team",
    
    "Voor onze vestiging in %s zijn wij per direct op zoek naar een %s. Als %s ben je betrokken bij het gehele ontwikkelproces, van concept tot oplevering. Je werkt aan diverse projecten voor onze klanten.\n\nFunctie-eisen:\n- HBO/WO denkniveau\n- Ervaring met moderne programmeertalen\n- Kennis van webontwikkeling\n- Teamplayer met goede communicatieve vaardigheden\n\nWij bieden:\n- Een uitdagende en afwisselende functie\n- Ruimte voor eigen initiatief\n- Goede primaire en secundaire arbeidsvoorwaarden\n- Een prettige werksfeer in een informeel team",
    
    "Wegens groei van ons bedrijf zijn wij op zoek naar een ervaren %s. In deze functie ben je verantwoordelijk voor het ontwikkelen van hoogwaardige software oplossingen voor onze klanten. Je werkt in een agile team en denkt mee over technische keuzes.\n\nWat we vragen:\n- Minimaal 3 jaar relevante werkervaring\n- Kennis van moderne frameworks\n- Analytisch denkvermogen\n- Passie voor technologie\n\nWat we bieden:\n- Een competitief salaris\n- 25 vakantiedagen\n- Persoonlijk opleidingsbudget\n- Vrijdagmiddagborrels\n- Een laptop naar keuze"
];

$contactpersonen = [
    'Jan Janssen',
    'Sander de Vries',
    'Erik de Vries',
    'Thomas van Dijk',
    'Pietje Puk',
    'Klaas de Vries',
    'Henk de Vries',
    'Kees de Vries',
    'Pietje Puk'
];

$stmt = $pdo->prepare('
    INSERT INTO vacatures (titel, bedrijf, locatie, omschrijving, contactpersoon) 
    VALUES (?, ?, ?, ?, ?)
');

$vacatureCount = 0;

foreach ($bedrijven as $bedrijf => $locaties) {
    $numVacatures = rand(3, 5);
    
    for ($i = 0; $i < $numVacatures && $vacatureCount < 100; $i++) {
        $functie = $functieTitels[array_rand($functieTitels)];
        $locatie = $locaties[array_rand($locaties)];
        $template = $omschrijvingsTemplates[array_rand($omschrijvingsTemplates)];
        
        $omschrijving = sprintf($template, $locatie, $functie, $functie);
        
        $contactpersoon = $contactpersonen[array_rand($contactpersonen)];
        
        $stmt->execute([$functie, $bedrijf, $locatie, $omschrijving, $contactpersoon]);
        $vacatureCount++;
    }
}

echo "Testdata toegevoegd: $vacatureCount vacatures van ongeveer " . count($bedrijven) . " bedrijven.\n";
echo "Database setup voltooid!\n"; 