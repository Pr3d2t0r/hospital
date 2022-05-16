<?php

class UsersModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'users';
    }

    public function getById($id, $mode = "ARRAY", $class = null)
    {
        $user = parent::getById($id, $mode, $class);
        if ($user == null) return null;
        switch ($mode){
            case "ARRAY":
                $user['permissions'] = unserialize($user['permissions']);
                break;
            case "OBJECT":
            case "OBJECTCLASS":
                $user->permissions = unserialize($user->permissions);
                break;
            default:
                throw new Exception("Choose a valid return type!");
        }
        return $user;
    }


    /**
     * @throws Exception
     */
    public function getByUsername($username, $mode="ARRAY", $class=null){
        return $this->get("username", $username, $mode, $class);
    }

    /**
     * @throws Exception
     */
    public function doctorExits($id)
    {
        return $this->get("doctor_id", $id) != null;
    }

    /**
     * @throws Exception
     */
    public function patientExits($id)
    {
        return $this->get("patient_id", $id) != null;
    }

    /**
     * @throws Exception
     */
    public function nurseExits($id)
    {
        return $this->get("nurse_id", $id) != null;
    }

    public function isNurse($userId){
        return $this->getById($userId)["nurse_id"] != null;
    }

    public function isDoctor($userId){
        return $this->getById($userId)["doctor_id"] != null;
    }

    public function getByDoctorId($id){
        return $this->get("doctor_id", $id);
    }
    public function getByNurseId($id){
        return $this->get("nurse_id", $id);
    }
    public function getByPatientId($id){
        return $this->get("nurse_id", $id);
    }

    public function unsetDoctor($id){
        $this->db->where('doctor_id', $id);
        return $this->db->update($this->table, ["doctor_id" => null]);
    }
    public function unsetNurse($id){
        $this->db->where('nurse_id', $id);
        return $this->db->update($this->table, ["nurse_id" => null]);
    }
    public function unsetPatient($id){
        $this->db->where('patient_id', $id);
        return $this->db->update($this->table, ["patient_id" => null]);
    }

    public function setDoctorId($userId, $doctorId){
        if (!$this->isDoctor($userId) ) {
            if (!$this->isNurse($userId)){
                $this->update($userId, ["doctor_id" => $doctorId]);
                return true;
            }else{
                return "USER_IS_NURSE";
            }
        }
        return "USER_ALREADY_ASSOC_WITH_MEDIC";
    }

    public function setNurseId($userId, $nurseId){
        if (!$this->nurseExits($nurseId)) {
            if (!$this->isDoctor($userId)) {
                $this->update($userId, ["nurse_id" => $nurseId]);
                return true;
            }else{
                return "USER_IS_MEDIC";
            }
        }
        return "USER_ALREADY_ASSOC_WITH_NURSE";
    }

    public function setPatientId($userId, $patientId){
        if (!$this->patientExits($patientId)) {
            $this->update($userId, ["patient_id" => $patientId]);
            return true;
        }
        return "USER_ALREADY_ASSOC_WITH_PATIENT";
    }

    public function insert($data)
    {
        $user = $this->getByUsername($data['username']);
        if ($user == null)
            return parent::insert($data);
        return "USERNAME_IS_BEING_USED";
    }
}
