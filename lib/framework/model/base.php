<?php
/**
 * Basis-Model von dem alle Models erben
 * @author skyfyre
 *
 */
Class Framework_Model_Base {
	/**
	 * Datenbank-Objekt
	 * @var object
	 */
	protected $db = NULL;
	
	public function __construct() {
		//eine Datenbank-Verbindung herstellen
		$this->db = Framework_Model_Database::getInstance();
	}
	
	
	//Platz für weitere Funktionen die in allen Models verfügbar sein sollen
	
	
}