<?php

class MY_Controller extends CI_Controller
{
    protected $user = null;
    protected bool $isLoggedIn = false;
    protected bool $isSuperAdmin = false;

    public function __construct()
    {
        parent::__construct();
        $userId = $this->isUserLogedIn();
        if ($userId) {
            $this->user = $this->UsersModel->getById($userId, "OBJECT");
            $this->isLoggedIn = true;
            $this->isSuperAdmin = $this->hasPermissions("SuperAdmin");
        }
    }

    private function isUserLogedIn(){
        $token = get_cookie('loginToken');
        if($token == null) return false;
        $userId = $this->UserTokensModel->getFromToken($token);
        if($userId == null) return false;
        $userId = $userId['user_id'];
        // verifica se ja está na altura de renovar o token
        if (get_cookie('loginToken_') != null)
            return $userId;
        // renova o token de login
        $strong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $strong));
        $this->UserTokensModel->insert([
            "user_id" => $userId,
            "token" => $token
        ]);
        // token valido por 7 dias
        //                                          Hora atual + 60 segundos * 60 minutos * 24 horas * 7 dias
        set_cookie("loginToken", $token, time() + 60 * 60 * 24 * 7, null, '/', null, true);
        set_cookie("loginToken_", '0', time() + 60 * 60 * 24 * 3, null, '/', null, true);
        return $userId;
    }

    protected function hasPermissions(string $needed, $userPermissions=null): bool
    {
        if (!$this->isLoggedIn) return false;
        if ($this->isSuperAdmin) return true;
        return in_array($needed, $userPermissions??$this->user->permissions);
    }
}