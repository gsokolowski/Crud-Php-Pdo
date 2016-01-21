<!DOCTYPE html>
<?php

    require 'Database.php';
    require 'Auth.php';
    require 'vendor/autoload.php';
    use Violin\Violin;

    $pdo = Database::connect();
    $auth = new Auth($pdo);
    if(!$auth->isLoggedIn()) {
        header("Location: index.php");
        die();
    }

    if ( !empty($_POST)) {
        // keep track validation errors
        $usernameError = null;
        $passwordError = null;
        $valid = true;

        // keep track post values
        $username = $_POST['username'];
        $password = $_POST['password'];

        $v = new Violin;
        $v->validate([
            'username'  => [$username, 'required|alpha|min(3)|max(20)'],
            'password'  => [$password, 'required|alpha|min(3)'],
        ]);

        if (!$v->passes()) {
            $usernameError = $v->errors()->get('username')[0];
            $passwordError = $v->errors()->get('password')[0];
            $valid = false;
        }

        $options = [
            "cost" => 12
        ];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO user (username, password) values(?, ?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($username, $hashedPassword));
            Database::disconnect();
            header("Location: index.php");
        }
    }
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Create a Customer</h3>
                    </div>
             
                    <form class="form-horizontal" action="create.php" method="post">
                      <div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
                        <label class="control-label">Username</label>
                        <div class="controls">
                            <input name="username" type="text"  placeholder="Username" value="<?php echo !empty($username)?$username:'';?>">
                            <?php if (!empty($usernameError)): ?>
                                <span class="help-inline"><?php echo  $usernameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
                        <label class="control-label">Password</label>
                        <div class="controls">
                            <input name="password" type="text" placeholder="password" value="<?php echo !empty($password)?$password:'';?>">
                            <?php if (!empty($passwordError)): ?>
                                <span class="help-inline"><?php echo $passwordError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn btn-default" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
    </div>
  </body>
</html>
