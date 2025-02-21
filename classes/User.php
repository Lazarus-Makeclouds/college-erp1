<?php
class User
{
    protected $_db;
    private $_data;
    private $_sessionName;
    private $_cookieName;
    private $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
                }
            }
        } else {
            $this->find($user);
            $this->_isLoggedIn = true;
        }
    }

    public function update($table, $id, $id_name, $fields = [])
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->Email;
        }

        if (!$this->_db->update($table, $id, $id_name, $fields)) {
            throw new Exception('There was a problem updating');
        }
    }

    public function create($table, $fields = [])
    {
        if (!$this->_db->insert($table, $fields)) {
            throw new Exception("There was a problem creating an account, Please Contact Support");
        }
    }

    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'reg_Id' : 'Email';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($Email = null, $Password = null, $remember = false)
    {
        if (!$Email && !$Password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->Reg_ID);
        } else {
            $user = $this->find($Email);
            if ($user) {
                if (Hash::check($this->data()->Password, $Password) || ($Password === $this->data()->Password)) {
                    Session::put($this->_sessionName, $this->data()->Reg_ID);

                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->Reg_ID));

                        if (!$hashCheck->count()) {
                            $this->_db->insert('user_session', array(
                                'user_id' => $this->data()->Reg_ID,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    function __call($method, $args)
    {
        if ($method == "hasPermission") {

            switch (count($args)) {
                case 1:
                    $group = $this->_db->get('groups', array('id', '=', $this->data()->group));

                    if ($group->count()) {
                        $permissions = json_decode($group->first()->permissions, true);
                        if (!empty($permissions)) {
                            if ($permissions[$args[0]] == true) {
                                return true;
                            }
                        }
                    }
                    return false;

                case 2:
                    $number = $this->_db->get('users',array("Email", "=", $args[1]))->first()->group;

                    $group = $this->_db->get('groups', array('id', '=', $number));

                    if ($group->count()) {
                        $permissions = json_decode($group->first()->permissions, true);
                        if (!empty($permissions)) {
                            if ($permissions[$args[0]] == true) {
                                return true;
                            }
                        }
                    }
                    return false;

            }
        }
    }


    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout()
    {

        $this->_db->delete('user_session', array('user_id', '=', $this->data()->Reg_ID));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public static function give_permision($id, $id_name, $permission)
    {
        $db = DB::getInstance();
        switch ($permission) {
            case "Standard":
                $db->update('users', $id, $id_name, ["group" => 1]);
                break;
            case "Super":
                $db->update('users',  $id, $id_name,["group" => 2]);
                break;
            case "Intern":
                $db->update('users',  $id, $id_name,["group" => 3]);
                break;
            case "admin":
                $db->update('users',  $id, $id_name,["group" => 4]);
                break;
            default:
                $db->update('users',  $id, $id_name,["group" => 1]);
                break;
        }
    }

    public function get_verification_status($id, $id_name){
        $student = $this->_db->get('users', array($id_name, '=', $id))->results();
        if (!empty($student)){
            return $student[0]->verified;
        }
    }

    public function get_verification_token($id, $id_name){
        $student = $this->_db->get('users', array($id_name, '=', $id))->results();
        if (!empty($student)){
            return $student[0]->verification_token;
        }
    }
}