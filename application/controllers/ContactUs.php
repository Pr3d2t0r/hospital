<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactUs extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session'));
        $this->load->model("ContactsModel");
        $this->load->helper('captcha');

    }

    public function index(){
        $data = [
            "title" => "Contact Us",
            "success"    => $this->session->flashdata("success_msg") ?? null
        ];

        $data['googleKey'] = "6LeWsDUfAAAAAMJhNyINrQ8a9uw0auRkCmuCPNW6";
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('content', 'Body', 'required|min_length[5]');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $data['formErrors'] = null;
            $secret = "6LeWsDUfAAAAAHgBniea3weTn_GjrpkdD7UpEbsE";

            $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));

            $userIp = $this->input->ip_address();

            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);

            $status = json_decode($output, true);

            if ($status['success']) {
                $result = $this->input->post();
                unset($result['g-recaptcha-response']);
                $this->ContactsModel->insert($result);
                $this->session->set_flashdata("success_msg", "Contact sent with success!");
            }else{
                $data['formErrors'] = '<p>Desculpe, Google Recaptcha falhou!</p>';
            }
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('contacts', $data);
        $this->load->view('commons/footer', $data);
    }
}
