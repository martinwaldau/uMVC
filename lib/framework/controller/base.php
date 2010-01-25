<?php
/**
 * Ausgangspunkt aller Controller, zentrale Elemente werden hier konfiguriert
 * @author skyfyre
 *
 */
Class Framework_Controller_Base {
	protected $_setArray = array();
	private $_layout = 'index';
	private $_view = 'main/index';
	
	
	public function __construct() {
		
	}
	
	
	/**
	 * Setzt Variablen für  die View
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	protected function set($key, $value) {
		$this->_setArray[$key] = $value;
		
		return TRUE;
	}
	
	
	/**
	 * Setzt die View, die später gerendert wird
	 * @param string $viewName
	 * @return boolean
	 */
	protected function setView($viewName) {
		$this->_view = strval($viewName);
		
		return TRUE;
	}
	
	
	/**
	 * Setzt das Layout, in dem die View gerendert wird
	 * @param string $layoutName
	 * @return boolean
	 */
	protected function setLayout($layoutName) {
		$this->_layout = strval($layout);
		
		return TRUE;
	}
	
	
	/**
	 * gibt das gesetzte Layout zurück
	 * @return string
	 */
	public function getLayout() {
		return $this->_layout;
	}
	
	
	/**
	 * gibt die gesetzten Variablen zurück
	 * @return array
	 */
	public function getSettedValues() {
		return $this->_setArray;
	}
	
	
	/**
	 * gibt die gesetzte View zurück
	 * @return string
	 */
	public function getView() {
		return $this->_view;
	}
	
}