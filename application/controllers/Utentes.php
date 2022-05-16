<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utentes extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session', 'model_errors'));
        $this->load->model(array("UtentesModel", "AddressModel"));

    }

    public function index(){
        $data = [
            "title"      => "Utentes",
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "isLoggedIn" => $this->isLoggedIn,
            "utentes"    => $this->AddressModel->_modelar_array($this->UtentesModel->getAll(mode:"OBJECT") ?? []),
            "success"    => $this->session->flashdata("success_msg") ?? null
        ];

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('utentes', $data);
        $this->load->view('commons/footer', $data);
    }

    public function add(){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("patients/");
            return;
        }
        $data = [
            "title" => "Add Patient"
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('n_utente', 'Nº de Utente', 'required|max_length[9]|min_length[9]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('birthday', 'Birthdate', 'required');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $result_arr = $this->input->post();
            $address_arr = [
                "name" => $result_arr['address'],
                "city" => $result_arr['city']
            ];
            unset($result_arr['address'], $result_arr['city']);

            $user = $this->UsersModel->getByUsername($result_arr['username']);
            unset($result_arr['username']);

            if ($user != null) {
                $config['upload_path'] = './uploads/patients/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $uploadData = $this->upload->data();
                    $result_arr['image_path'] = $uploadData['file_name'];
                    $result_arr["address_id"] = $this->AddressModel->insert($address_arr);
                    $patient_id = $this->UtentesModel->insert($result_arr);
                    $error = $this->model_errors::whatIf($this->UsersModel->setPatientId($user['id'], $patient_id));
                    $data["formErrors"] = $error;
                    if ($data['formErrors'] == null) {
                        $this->session->set_flashdata("success_msg", "Patient added with success!");
                        redirect('patients/');
                    }
                } else {
                    $data['formErrors'] = $this->upload->display_errors();
                }
            }else{
                $data['formErrors'] = "<p>Username doesn't correspond to any user!</p>";
            }
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('utentes_add', $data);
        $this->load->view('commons/footer', $data);
    }

    public function edit($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("patients/");
            return;
        }
        $data = [
            "title"    => "Edit Patient",
            "patient"   => $this->UtentesModel->getById($id),
            "success"  => $this->session->flashdata("success_msg") ?? null,
            "id"       => $id
        ];
        $data["addr"] = $this->AddressModel->getById($data['patient']['address_id']);

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('n_utente', 'Nº de Utente', 'required|max_length[9]|min_length[9]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('birthday', 'Birthdate', 'required');

        if (!$this->form_validation->run()) {
            $data['formErrors'] = validation_errors();
        } else {
            $result_arr = $this->input->post();
            $address_arr = [
                "name" => $result_arr['address'],
                "city" => $result_arr['city']
            ];
            unset($result_arr['address'], $result_arr['city']);


            $config['upload_path'] = './uploads/patients/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $result_arr['image_path'] = $uploadData['file_name'];
            } else {
                $data['formErrors'] = $this->upload->display_errors();
            }
            $this->AddressModel->update($data['patient']['address_id'], $address_arr);
            $this->UtentesModel->update($id, $result_arr);
            $this->session->set_flashdata("success_msg", "Patients edited with success!");
            redirect('patients/');
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('utentes_edit', $data);
        $this->load->view('commons/footer', $data);
    }

    public function delete($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("patients/");
            return;
        }
        $patient = $this->UtentesModel->getById($id);
        if ($patient != null) {
            $this->UsersModel->unsetNurse($id);
            $this->AddressModel->delete($patient['address_id']);
            if ($this->UtentesModel->delete($id)) {
                $this->session->set_flashdata("success_msg", "Patient removed with success!");
                redirect("patients/");
                return;
            }
        }
        $this->session->set_flashdata("error_msg", "Something went wrong!");
        redirect("patients/");
    }
}
