<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Enfermeiros extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session', 'model_errors'));
        $this->load->model(array("NursesModel", "AddressModel"));

    }

    public function index(){
        $data = [
            "title"      => "Nurses",
            "hasAdmin" => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "isLoggedIn" => $this->isLoggedIn,
            "nurses"     => $this->AddressModel->_modelar_array($this->NursesModel->getAll(mode:"OBJECT") ?? []),
            "success"    => $this->session->flashdata("success_msg") ?? null
        ];

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('nurses', $data);
        $this->load->view('commons/footer', $data);
    }

    public function add(){
        if (!$this->isSuperAdmin){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("nurses/");
            return;
        }
        $data = [
            "title" => "Add Nurse"
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('nif', 'Nif', 'required|max_length[9]|min_length[9]');
        $this->form_validation->set_rules('specialty', 'Specialty', 'required|min_length[5]|max_length[100]');
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
//            var_dump($result_arr);
//            var_dump($address_arr);

            $user = $this->UsersModel->getByUsername($result_arr['username']);
            unset($result_arr['username']);

            if ($user != null) {
                $config['upload_path'] = './uploads/nurses/';
                $config['allowed_types'] = 'pdf|jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $uploadData = $this->upload->data();
                    $result_arr['image_path'] = $uploadData['file_name'];
                    $result_arr["address_id"] = $this->AddressModel->insert($address_arr);
                    if(!$this->UsersModel->isNurse($user['id'])){
                        $nurse_id = $this->NursesModel->insert($result_arr);
                        $error = $this->model_errors::whatIf($this->UsersModel->setNurseId($user['id'], $nurse_id));
                        $data["formErrors"] = $error;
                        if ($data['formErrors'] == null) {
                            $this->session->set_flashdata("success_msg", "Nurse added with success!");
                            redirect('nurses/');
                        }
                    }else{
                        $data["formErrors"] = "User you're trying to assoc is already a nurse!";
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
        $this->load->view('nurses_add', $data);
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
