<?php
class Register extends CI_Controller{
	function __construct(){
		parent::__construct();
	}

	function Index(){
        echo $this->load->view("panelbackend/register", null, true);
	}
}
