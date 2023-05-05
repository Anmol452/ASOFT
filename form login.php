<form action="login.php" method="post">

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$phone = $_POST["phone"];
    $password = $_POST["password"];

    $sql = "select * from users where phone='$phone' AND password='$password'";

  $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
                $LOGIN = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['phone'] = $phone;
                header('location:gagu.php');
            } 
            
            
            
