<?php
Class Controller_Main extends Controller_Controller {
	protected $Main = NULL;
	
	
	public function __construct() {
		parent::__construct();
	
	}
	
	
	public function index() {
		$this->set('test', 'something');
		
		return TRUE;
	}
	
	
	public function links() {
		$this->setView('main/links');
	}
	
	
	public function terms() {
		$this->setView('main/terms');
	}
	
	
	public function imprint() {
		$this->setView('main/imprint');
	}
	
	
	public function contact() {
		$this->setView('main/contact');
	}
}