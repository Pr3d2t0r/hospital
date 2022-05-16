<?php

/**
 * Class PasswordHash
 * Encripta Passwords
 * @author Rafael Velosa
 */
class Passwordhash{
    /**
     * Serve para delimitar a password em duas partes
     * @var int
     */
    private int $count;

    /**
     * PasswordHash constructor.
     * @param int $count
     */
    public function __construct($count=5){
        $this->count = $count;
    }

    /**
     * Encripta uma password
     * @param $password
     * @return false|string|null
     */
    public function encrypt($password){
        $preparedPassword = $this->preparePassword($password);
   /*     $readyPassword = password_hash($preparedPassword, PASSWORD_BCRYPT);
        unset($preparedPassword);
        return $readyPassword;*/
        return sha1($preparedPassword);
    }

    /**
     * Verifica de uma password é valida
     * @param $password
     * @param $hash
     * @return bool
     */
    public function check($password, $hash){
        $preparedPassword = $this->preparePassword($password);
        /*$result = password_verify($preparedPassword, $hash);
        unset($preparedPassword);
        return $result;*/
        return sha1($preparedPassword) == $hash;
    }

    /**
     * Prepara uma password para ser encriptada
     * @param $password
     * @return string
     */
    private function preparePassword($password){
        // inverte a password
        $password = strrev($password);
        if (strlen($password) > $this->count){
            $part1 = substr($password,0, $this->count);
            $part2 = substr($password, $this->count);
            $part1 = sha1($part1);
            $part2 = sha1($part2);
            unset($password);
            return strrev($part1).strrev($part2);
        }
        $password = sha1($password);
        return strrev($password);
    }
}