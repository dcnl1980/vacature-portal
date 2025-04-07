<?php
/**
 * Database klasse
 * 
 * Deze klasse zorgt voor de verbinding met de SQLite database
 * en biedt methoden voor het uitvoeren van queries.
 * 
 * @author: Chris van Steenbergen
 */
class Database {
    private $pdo;
    
    /**
     * Constructor: initialiseert de database verbinding
     * 
     * @author: Chris van Steenbergen
     */
    public function __construct() {
        $this->pdo = new PDO('sqlite:'.__DIR__.'/../../data/database.sqlite');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Voert een SQL query uit met optionele parameters
     * 
     * @param string $sql De SQL query die uitgevoerd moet worden
     * @param array $params Array met parameters voor de prepared statement
     * @return PDOStatement Het resultaat van de query
     * 
     * @author: Chris van Steenbergen
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
} 