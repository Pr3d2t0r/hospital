<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consultas extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session'));
        $this->load->model(array("ConsultationsModel", "MedicosModel", "UtentesModel"));

    }

    public function index(){
        $data = [
            "title"     => "Consultas",
            "success"   => $this->session->flashdata("success_msg") ?? null,
            "isLoggedIn" => $this->isLoggedIn,
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "consultas" => $this->ConsultationsModel->getForToday("OBJECT") ?? []
        ];

        for($i = 0; $i < count($data['consultas']); $i++) {
            $data['consultas'][$i]->doctor_name = $this->MedicosModel->getById($data['consultas'][$i]->doctor_id)['name'];
            $data['consultas'][$i]->patient_name = $this->UtentesModel->getById($data['consultas'][$i]->patient_id)['name'];
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('consultations', $data);
        $this->load->view('commons/footer', $data);
    }

    public function all(){
        $data = [
            "title"     => "Consultas",
            "success"   => $this->session->flashdata("success_msg") ?? null,
            "isLoggedIn" => $this->isLoggedIn,
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "consultas" => $this->ConsultationsModel->getAll('id', 'asc', "OBJECT") ?? []
        ];

        for($i = 0; $i < count($data['consultas']); $i++) {
            $data['consultas'][$i]->doctor_name = $this->MedicosModel->getById($data['consultas'][$i]->doctor_id)['name'];
            $data['consultas'][$i]->patient_name = $this->UtentesModel->getById($data['consultas'][$i]->patient_id)['name'];
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('consultations_all', $data);
        $this->load->view('commons/footer', $data);
    }

    public function add(){
        if (!$this->isSuperAdmin && $this->user->doctor_id == null){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("/");
            return;
        }
        $data = [
            "title"        => "Add Consultation",
            "success"      => $this->session->flashdata("success_msg") ?? null,
            "isSuperAdmin" => $this->isSuperAdmin,
            "isLoggedIn" => $this->isLoggedIn,
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "doctors"      => $this->MedicosModel->getAll('id', 'asc', "OBJECT")
        ];

        if ($this->isSuperAdmin)
            $this->form_validation->set_rules('doctor_id', 'Doctor', 'required');
        $this->form_validation->set_rules('n_utente', 'Nº de Utente', 'required|min_length[9]|max_length[9]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $result_arr = $this->input->post();
            $patient = $this->UtentesModel->getByNumUtente($result_arr['n_utente']);
            unset($result_arr['n_utente']);
            if ($patient != null) {
                $result_arr['patient_id'] = $patient['id'];
                if (!$this->isSuperAdmin)
                    $result_arr['doctor_id'] = $this->user->doctor_id;
                $this->ConsultationsModel->insert($result_arr);
                $this->session->set_flashdata("success_msg", "Consultation registered with success!");
                redirect('/consultations');
            }else{
                $data['formErrors'] = "<p>Patient is not registered!</p>";
            }
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('consultations_add', $data);
        $this->load->view('commons/footer', $data);
    }

    public function edit($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("nurses/");
            return;
        }
        $data = [
            "title"    => "Add Nurse",
            "nurse"   => $this->NursesModel->getById($id),
            "isLoggedIn" => $this->isLoggedIn,
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "success"  => $this->session->flashdata("success_msg") ?? null,
            "id"       => $id
        ];

        $data["addr"] = $this->AddressModel->getById($data['nurse']['address_id']);

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('nif', 'Nif', 'required|max_length[9]|min_length[9]');
        $this->form_validation->set_rules('specialty', 'Specialty', 'required|min_length[5]|max_length[100]');
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


            $config['upload_path'] = './uploads/nurses/';
            $config['allowed_types'] = 'pdf|jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $result_arr['image_path'] = $uploadData['file_name'];
            } else {
                $data['formErrors'] = $this->upload->display_errors();
            }
            $this->AddressModel->update($data['nurse']['address_id'], $address_arr);
            $this->NursesModel->update($id, $result_arr);
            $this->session->set_flashdata("success_msg", "Nurse edited with success!");
            redirect('nurses/');
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('nurses_edit', $data);
        $this->load->view('commons/footer', $data);
    }

    public function delete($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("nurses/");
            return;
        }
        $nurse = $this->NursesModel->getById($id);
        if ($nurse != null) {
            $this->UsersModel->unsetNurse($id);
            $this->AddressModel->delete($nurse['address_id']);
            if ($this->NursesModel->delete($id)) {
                $this->session->set_flashdata("success_msg", "Nurse removed with success!");
                redirect("nurses/");
                return;
            }
        }
        $this->session->set_flashdata("success_msg", "Something went wrong!");
        redirect("nurses/");
    }
}
