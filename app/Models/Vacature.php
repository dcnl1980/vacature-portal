<?php
/**
 * Vacature model
 * 
 * Deze klasse bevat alle logica voor het ophalen en manipuleren van vacature gegevens
 * 
 * @author: Chris van Steenbergen
 */
class Vacature {
    private $db;
    
    /**
     * Constructor: initialiseert database connectie
     * 
     * @author: Chris van Steenbergen
     */
    public function __construct() {
        require_once __DIR__ . '/../Core/Database.php';
        $this->db = new Database();
    }
    
    /**
     * Haalt alle vacatures op met optionele zoekfilters
     * 
     * @param string|null $searchTerm Zoekterm voor titel en omschrijving
     * @param string|null $location Zoekterm voor locatie
     * @return array Lijst met vacatures die voldoen aan de zoekcriteria
     */
    public function getVacatures($searchTerm = null, $location = null) {
        $sql = "SELECT * FROM vacatures WHERE 1=1";
        $params = [];
        
        if ($searchTerm) {
            $sql .= " AND (titel LIKE ? OR omschrijving LIKE ? OR bedrijf LIKE ?)";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
        }
        
        if ($location) {
            $sql .= " AND locatie LIKE ?";
            $params[] = "%$location%";
        }
        
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Haalt een specifieke vacature op op basis van ID
     * 
     * @param int $id ID van de vacature
     * @return array|false Vacature gegevens of false als vacature niet bestaat
     * 
     * @author: Chris van Steenbergen
     */
    public function getVacatureById($id) {
        $sql = "SELECT * FROM vacatures WHERE id = ?";
        $result = $this->db->query($sql, [$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
} 