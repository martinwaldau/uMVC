<?php
Class Model_Category extends Model_Base {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Liest Daten anhand der gegebenen Kategorie wieder aus
	 * @param string $category
	 * @return array
	 */
	public function getCategory($category) {
		if (!is_string($category)) return FALSE;
		
		$pains = $this->_getCategoryData($category);
		if (!$pains) return array();
		else return $pains;
		
	}
	
	
	protected function _getCategoryData($category) {
		$query = 'SELECT * FROM allpains WHERE type = \'' . $this->db->escape($category) . '\' ORDER by nr DESC';
		$pains = $this->db->query($query);
		
		return $pains;
	}
}