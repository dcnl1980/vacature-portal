<?php
/**
 * Sollicitatie model
 * 
 * Deze klasse bevat alle logica voor het verwerken en opslaan van sollicitaties
 * 
 * @author: Chris van Steenbergen
 */
class Sollicitatie {
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
     * Slaat een nieuwe sollicitatie op in de database
     * 
     * @param int $vacatureId ID van de vacature waarop wordt gesolliciteerd
     * @param string $naam Naam van de sollicitant
     * @param string $email Email van de sollicitant
     * @param array $cvFile CV bestand ($_FILES['cv'])
     * @param string $motivatie Motivatie van de sollicitant
     * @return bool|string True bij succes, foutmelding bij falen
     * 
     * @author: Chris van Steenbergen
     */
    public function opslaan($vacatureId, $naam, $email, $cvFile, $motivatie) {
        try {
            // Valideer het CV bestand
            $uploadResult = $this->uploadCV($cvFile);
            
            // Bekijk of de uploadResult variable een path teruggeeft of een foutmelding
            if (is_string($uploadResult) && strpos($uploadResult, 'uploads/cv/') !== 0) {
                // Het is een foutmelding, geen geldig pad
                error_log('Upload error: ' . $uploadResult);
                return $uploadResult;
            }
            
            // Als de uploadResult een path teruggeeft, sla deze op in de database
            $cvPath = $uploadResult;
            
            $sql = "INSERT INTO sollicitaties (vacature_id, naam, email, cv_path, motivatie) 
                    VALUES (?, ?, ?, ?, ?)";
                    
            $this->db->query($sql, [
                $vacatureId,
                $naam,
                $email,
                $cvPath,
                $motivatie
            ]);
            
            return true;
        } catch (Exception $e) {
            error_log('Database error: ' . $e->getMessage());
            return "Er is een fout opgetreden bij het opslaan van de sollicitatie.";
        }
    }
    
    /**
     * Verwerkt het uploaden van een CV bestand met beveiligingscontroles
     * 
     * @param array $file Het bestand uit $_FILES
     * @return string|bool Pad naar het opgeslagen bestand of foutmelding
     * 
     * @author: Chris van Steenbergen
     */
    private function uploadCV($file) {
        // Controleer of er een bestand is geüpload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Fout bij het uploaden van het bestand: " . $this->getUploadErrorMessage($file['error']);
        }
        
        // Controleer bestandstype
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return "Alleen PDF en Word bestanden zijn toegestaan";
        }
        
        // Controleer bestandsgrootte (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxSize) {
            return "Bestandsgrootte mag maximaal 2MB zijn";
        }
        
        // Genereer een unieke bestandsnaam
        $fileName = time() . '_' . bin2hex(random_bytes(8)) . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file['name']);
        $uploadDir = __DIR__ . '/../../uploads/cv/';
        $targetPath = $uploadDir . $fileName;
        
        // Controleer of de map bestaat en maak deze aan indien nodig
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Verplaats het bestand
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return "Fout bij het opslaan van het bestand";
        }
        return 'uploads/cv/' . $fileName;
    }
    
    /**
     * Vertaalt upload error codes naar leesbare berichten
     * 
     * @param int $errorCode De PHP upload error code
     * @return string Leesbaar foutbericht  
     * 
     * @author: Chris van Steenbergen
     */
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "Het bestand is te groot volgens de server instellingen";
            case UPLOAD_ERR_FORM_SIZE:
                return "Het bestand is te groot volgens het formulier";
            case UPLOAD_ERR_PARTIAL:
                return "Het bestand is slechts gedeeltelijk geüpload";
            case UPLOAD_ERR_NO_FILE:
                return "Er is geen bestand geüpload";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Er is geen tijdelijke map beschikbaar op de server";
            case UPLOAD_ERR_CANT_WRITE:
                return "Kan het bestand niet opslaan op de server";
            case UPLOAD_ERR_EXTENSION:
                return "Bestand upload gestopt door een PHP extensie";
            default:
                return "Onbekende fout bij het uploaden";
        }
    }
} 