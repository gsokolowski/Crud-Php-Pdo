<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link  href="css/bootstrap.min.css" rel="stylesheet">
    <link  href="css/style.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
            <div class="row">
                <h3>Crud Php Pdo + Login</h3>
            </div>
            <?php
                require 'Database.php';
                require 'Auth.php';
                $pdo = Database::connect();
                $auth = new Auth($pdo);
            ?>
            <div class="<?= $auth->isLoggedIn() ? "loggedIn" : "loggedOut"; ?>">
            <?php
                require 'loginForm.php'
            ?>
            </div>
            <?php
                if($auth->isLoggedIn()) {
            ?>
            <div class="row">
                <p align="right">
                    <a href="?logout" class="btn btn-info">Logout</a>
                </p>
                <p>
                    <a href="create.php" class="btn btn-success">Create User</a>
                </p>
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Password</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       $sql = 'SELECT * FROM user ORDER BY id DESC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['username'] . '</td>';
                                echo '<td>'. $row['password'] . '</td>';
                                echo '<td width=250>';
                                echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
                                echo ' ';
                                echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
            </div>
            <?php } ?>
    </div>
  </body>
</html>