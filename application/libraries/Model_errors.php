<?php

class Model_errors
{
    static array $errors = [
        "USER_IS_NURSE"                   => "The user account you are trying to sync is already a nurse, it can't be a doctor and a nurse at the same time!",
        "USER_ALREADY_ASSOC_WITH_MEDIC"   => "The user account you are trying to sync is already a doctor!",
        "USER_IS_MEDIC"                   => "The user account you are trying to sync is already a doctor, it can't be a doctor and a nurse at the same time!",
        "USER_ALREADY_ASSOC_WITH_NURSE"   => "The user account you are trying to sync is already a nurse!",
        "USER_ALREADY_ASSOC_WITH_PATIENT" => "The user account you are trying to sync is already a patient!",
    ];

    public static function whatIf($errorCode){
        if(!is_string($errorCode))
            return null;
        if (isset(Model_errors::$errors[$errorCode]))
            return Model_errors::$errors[$errorCode];
        return "Something Went Wrong [MECLASS]!";
    }

}