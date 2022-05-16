<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Medicos extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session', 'model_errors'));
        $this->load->model(array("MedicosModel", "AddressModel"));

    }

    public function index(){
        $data = [
            "title"       => "Medics",
            "isLoggedIn"  => $this->isLoggedIn,
            "hasAdmin"    => $this->isSuperAdmin || $this->hasPermissions("Admin"),
            "medicos"     => $this->AddressModel->_modelar_array($this->MedicosModel->getAll('id', 'asc', "OBJECT") ?? []),
            "success"     => $this->session->flashdata("success_msg") ?? null,
            "edit_url"    => base_url('doctors/edit/'),
            "remove_url"  => base_url('doctors/remove/'),
            "seemore_url" => base_url('doctors/'),
        ];
        $this->load->library('parser');
        $m = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../templates')
        ));
        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
//        $this->parser->parse('medicos', $data);
        $template = $m->loadTemplate('medicos');
        echo $template->render($data);
        $this->load->view('commons/footer', $data);
    }

    public function add(){
        if (!$this->isSuperAdmin){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("doctors/");
            return;
        }
        $data = [
            "title" => "Add Doctor"
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
//        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('nif', 'Nif', 'required|max_length[9]|min_length[9]');
        $this->form_validation->set_rules('specialty', 'Specialty', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('birthday', 'Birthdate', 'required');
//        $this->form_validation->set_rules('image', 'Image', 'required');

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
                $config['upload_path'] = './uploads/doctors/';
                $config['allowed_types'] = 'pdf|jpg|png|jpeg';
                /*$config['max_size'] = '1024';
                $config['max_width']  = '1024';
                $config['max_height']  = '768';*/
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $uploadData = $this->upload->data();
                    $result_arr['image_path'] = $uploadData['file_name'];
                    $result_arr["address_id"] = $this->AddressModel->insert($address_arr);
                    if(!$this->UsersModel->isDoctor($user['id'])){
                        $medic_id = $this->MedicosModel->insert($result_arr);
                        $error = $this->model_errors::whatIf($this->UsersModel->setDoctorId($user['id'], $medic_id));
                        $data["formErrors"] = $error;
                        if ($data['formErrors'] == null) {
                            $this->session->set_flashdata("success_msg", "Doctor added with success!");
                            redirect('doctors/');
                        }
                    }else{
                        $data["formErrors"] = "User you're trying to assoc is already a doctor!";
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
        $this->load->view('medicos_add', $data);
        $this->load->view('commons/footer', $data);
    }

    public function edit($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("doctors/");
            return;
        }
        $data = [
            "title"    => "Edit Doctor",
            "medico"   => $this->MedicosModel->getById($id),
            "success"  => $this->session->flashdata("success_msg") ?? null,
            "id"       => $id
        ];

        $data["addr"] = $this->AddressModel->getById($data['medico']['address_id']);

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


            $config['upload_path'] = './uploads/doctors/';
            $config['allowed_types'] = 'pdf|jpg|png|jpeg';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $result_arr['image_path'] = $uploadData['file_name'];
            } else {
                $data['formErrors'] = $this->upload->display_errors();
            }
            $this->AddressModel->update($data['medico']['address_id'], $address_arr);
            $this->MedicosModel->update($id, $result_arr);
            $this->session->set_flashdata("success_msg", "Doctor edited with success!");
            redirect('medicos/');
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('medicos_edit', $data);
        $this->load->view('commons/footer', $data);
    }

    public function delete($id){
        if (!$this->hasPermissions("Admin")){
            $this->session->set_flashdata("success_msg", "Access Forbidden!");
            redirect("doctors/");
            return;
        }
        $medic = $this->MedicosModel->getById($id);
        if ($medic != null) {
            $this->UsersModel->unsetDoctor($id);
            $this->AddressModel->delete($medic['address_id']);
            $deleted = $this->MedicosModel->delete($id);
            if ($deleted) {
                $this->session->set_flashdata("success_msg", "Doctor removed with success!");
                redirect("doctors/");
                return;
            }
        }
        $this->session->set_flashdata("success_msg", "Something went wrong!");
        redirect("doctors/");
    }
}
