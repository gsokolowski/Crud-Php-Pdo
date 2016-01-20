<?php

class Auth {

    private $pdo;

    public function __construct($pdo) {
        session_start();
        $this->pdo = $pdo;

        if (isset($_GET["logout"])) {
            $this->logout();
        }
        elseif (isset($_POST["login"])) {
            $this->login();
        }
    }

    private function login(){

        if (!empty($_POST['username']) && !empty($_POST['password'])) {

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select * from user WHERE username = '".$_POST['username']. "' and password ='". $_POST['password']."'" ;
            //echo $sql;
            $query = $this->pdo->prepare($sql);
            $query->execute(array());
            $data = $query->fetch(PDO::FETCH_ASSOC);

            //var_dump($data);
            if($data) {
                $_SESSION['username'] = $data['username'];
                $_SESSION['password'] = $data['password'];
                $_SESSION['isUserLoggedIn'] = true;
                //echo 'user exists, session created';
                return true;
            }
            return false;
        }
    }

    private function logout() {
        session_unset();
        session_destroy();
        //echo 'session destroyed, user logged out';
    }

    public function isLoggedIn() {
        if(isset($_SESSION['isUserLoggedIn'])) {
            return $_SESSION['isUserLoggedIn'] ? true : false;
        }
    }

}