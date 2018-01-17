<?php

class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = new PDO("sqlite:".dirname(__FILE__)."/db/database.db");
    }

    public function user_exist($username){
        $username=strtolower($username);
        $query = $this->db->prepare("SELECT COUNT (id) FROM accounts WHERE username = ?");
        $query->execute([$username]);
        return (bool)$query->fetchColumn();
    }


    public function user_data($username){
        $username=strtolower($username);
        if ($this->user_exist($username)){
            $query = $this->db->prepare("SELECT * FROM accounts WHERE username = ?");
            $query->execute([$username]);
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        else return false;
    }

    private function random_string(){
        $string="";
        $chars = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $length= rand(20,24);
        for ($i = 0; $i < $length; $i++){
            $string .= substr($chars, rand(0, strlen($chars)-1),1);
        }
        return $string;
    }

    private function upd_token($username){

        if ($this->user_exist($username)){
          $token=$this->random_string();
          $exp_time=time()+(60*60*24*7); //7 days token time
          setcookie("token",$token);

          $sql = "UPDATE accounts SET token = :token, token_time = :token_time WHERE username= :username";
          $query = $this->db->prepare($sql);
          $query->bindParam(':token', $token);
          $query->bindParam(':token_time', $exp_time);
          $query->bindParam(':username', $username);
          return $query->execute();
        }
        else return false;
    }

    private function del_token($username){
        if ($this->user_exist($username)){
            $token="none";
            $exp_time=0;
            setcookie("token","",1);

            $sql = "UPDATE accounts SET token = :token, token_time = :token_time WHERE username= :username";
            $query = $this->db->prepare($sql);
            $query->bindParam(':token', $token);
            $query->bindParam(':token_time', $exp_time);
            $query->bindParam(':username', $username);
            return $query->execute();
        }
        else return false;
    }

    private function token_valid($token){
            $query = $this->db->prepare("SELECT COUNT(id) FROM accounts WHERE token = ?");
            $tExist = (bool)$query->execute([$token]);
            if (!$tExist){
                return false;
            }
            else{
                $query = $this->db->prepare("SELECT * FROM accounts WHERE token = ?");
                $query->execute([$token]);
                $userdata=$query->fetch(PDO::FETCH_ASSOC);
                if ($userdata["token_time"]<time()) return false;
                else return $userdata["username"];
            }
    }

    public function get_id($username){
        $username=strtolower($username);
        if ($this->user_exist($username)){
            $query = $this->db->prepare("SELECT id FROM accounts WHERE username = ?");
            $query->execute([$username]);
            return $query->fetchColumn();
        }
        else return false;

    }

    public function sign_up($username, $password){
        $username=strtolower($username);
        $result=[
            "result" => true,
            "errors" => [],
        ];
        if ($this->user_exist($username)){
            $result["result"] = false;
            $result['errors'][]= "1"; //1 if user already exist
        }

        if ($result["result"]){
            $userdata = [
                "username" => strtolower($username),
                "password" => password_hash("$password", PASSWORD_DEFAULT),
            ];
            if (!$this->db->prepare("INSERT INTO accounts (username, password) VALUES (?, ?);")->execute(
                [
                    $userdata["username"],
                    $userdata["password"],
                ]
            )){
                $result["result"] = false;
                $result["errors"][] = "0"; //0 if db trouble
            }
            else {
                $q= $this->upd_token($username);
                if (!$q){
                    $result["result"] = false;
                    $result["errors"][] = "2"; // 2 if db trouble with token
                }
            }
        }
        return $result;
    }

    public function sign_in($username, $password){
        $username=strtolower($username);
        $result=[
            "result" => true,
            "errors" =>[],
        ];
        if (!$this->user_exist($username)){
            $result["result"] = false;
            $result["errors"][] = "1"; //1 if user not exist
        }
        if ($result["result"]){
            $userdata=$this->user_data($username);
            if ($userdata!==false){
                if (password_verify($password, $userdata["password"])){
                    $this->upd_token($username);
                }
                else {
                    $result["result"] = false;
                    $result["errors"][] = "1"; //1 if password wrong
                }
            }
            else {
                $result["result"]=false;
                $result["errors"][] = "0"; //0 if user exist but not exist/db trouble
            }
        }
        return $result;
    }

    public function logout($username){
        $result = [
            "result" => false,
            "errors" => [],
        ];

        if (!$this->in_system()){
            $result["errors"][] = "0";
            return $result;
        }

        if (isset($_COOKIE['token'])){
            $this->del_token($username);
        }
        setcookie("token","");
        $result["result"] = true;
        return $result;
    }

    public function in_system(){//return false (not in system) or userdata (in system)
        $result = false;
        if (isset($_COOKIE['token'])){
            $username = $this->token_valid($_COOKIE['token']);
            if ($username) return $username;
        }
        return $result;
    }

}

?>