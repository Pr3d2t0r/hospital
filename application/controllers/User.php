<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session', 'passwordhash'));
    }

    public function register(){
        $data = [
            "title" => "Register User",
            "isLoggedIn" => $this->isLoggedIn
        ];

        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'required|matches[password]');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $data['formErrors'] = null;
            $this->session->set_flashdata("success_msg", "User Registered with success!");
            $result_arr = $this->input->post();
            unset($result_arr['password_repeat']);
            $result_arr['password'] = $this->passwordhash->encrypt($result_arr['password']);
            $result_arr["permissions"] = serialize(["Any"]);
            $result_arr['active'] = 1;
            $this->UsersModel->insert($result_arr);
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('users_register', $data);
        $this->load->view('commons/footer', $data);
    }

    public function login(){
        if ($this->isLoggedIn) {
            redirect("/");
            return;
        }

        $data = [
            "title" => "Login"
        ];

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $data['formErrors'] = null;
            $result_arr = $this->input->post();
            $user = $this->UsersModel->getByUsername($result_arr['username']);
            if ($user != null) {
                $success = $this->passwordhash->check($result_arr['password'], $user['password']);
                if ($success) {
                    $strong = true;
                    $token = bin2hex(openssl_random_pseudo_bytes(64, $strong));
                    $this->UserTokensModel->insert([
                        "user_id" => $user['id'],
                        "token" => $token
                    ]);// token valido por 7 dias
                    //                                          Hora atual + 60 segundos * 60 minutos * 24 horas * 7 dias
                    set_cookie("loginToken", $token, time() + 60 * 60 * 24 * 7, null, '/', null, true);
                    set_cookie("loginToken_", '0', time() + 60 * 60 * 24 * 3, null, '/', null, true);
                    $this->session->set_flashdata("success_msg", "User logged in with success!");
                    redirect("/");
                } else {
                    $data['formErrors'] = "<p>Incorrect credentials! [password]</p>";
                }
            }else{
                $data['formErrors'] = "<p>Incorrect credentials! [username]</p>";
            }
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('users_login', $data);
        $this->load->view('commons/footer', $data);
    }

    public function logout(){
        if ($this->isLoggedIn) {
            $this->UserTokensModel->delete(get_cookie("loginToken"));
            set_cookie("loginToken", '0', time() - 3600);
            set_cookie("loginToken_", '0', time() - 3600);
        }
        $this->session->set_flashdata("success_msg", "User logged out with success!");
        redirect("/login");
    }
}
