<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
        $data = [
            "title" => "Hospital"
        ];
		$this->load->view('commons/header', $data);
		$this->load->view('commons/menu', $data);
        $this->load->view('home', $data);
        $this->load->view('commons/footer', $data);
    }
}
