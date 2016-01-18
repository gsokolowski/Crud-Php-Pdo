<?php

class Auth {

// STEPS
// ). set session_start() in constructor
// 1. Connect with db to have access to user table to be able ask for name an pass and check if exist
// 2. If connection exist take username nad password passed from forms validate
// 3. Function login() Make a call to db if user exists with this password
// 4. If exists then username and pass i correct so save username and password in $_SESSION[], session holds user,
// one session per user
// 5. Function isLoggedIn() to check if user is logged in $_SESSION[]. If yes then logged in if not the not
// 6. Function logout() simply remove user session, one session per user and when you distroy session user logged out

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

    public function login(){

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

    public function logout() {
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