<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
        $data = [

        ];
		$this->load->view('commons/header');
		$this->load->view('commons/menu');
        $this->load->view('home');
        $this->load->view('commons/footer');
    }
}
