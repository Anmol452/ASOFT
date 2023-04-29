<?php
$error = false;
$showerror = false;
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $username = $_POST['uname'];
    $phone = $_POST['phone'];
    $password = $_POST['pass'];
    $cpassword = $_POST['cpass'];

    $existsql = "SELECT * FROM `adiss`WHERE phone = '$phone'";
    $existsql = "SELECT * FROM `adiss`WHERE username = '$username'";
    $result = mysqli_query($conn, $existsql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showerror = true;
    } else {


        if ($password == $cpassword) {
            $sql = "INSERT INTO `adiss` ( `username`, `password`, `phone`) VALUES ('$username', '$password', '$phone')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                session_start();
                $_SESSION['loggeden'] = true;
                $_SESSION['phone'] = $phone;
                header('location:forum.php');
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }


    }
}







?>


<?php
    if ($error) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Comforam password rong!</strong> enter same password .
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
        ';
    }
    ?>

<?php
    if ($showerror) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>phone or user name alrady!</strong> alrady use number or user name  .
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
        ';
    }
    ?>
