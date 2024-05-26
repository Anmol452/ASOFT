
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    // File upload
    $filename = basename($_FILES["uploadfile"]["name"]);
    $tmpname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "images/" . $filename;

    // Create the images directory if it doesn't exist
    if (!is_dir('images')) {
        mkdir('images', 0755, true);
    }

    if (!move_uploaded_file($tmpname, $folder)) {
        echo 'Failed to upload file.';
        exit();
    }

    // Get user inputs
    $fastname = $_POST['fastname'];
    $lastname = $_POST['lastname'];
    $email1 = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $image_path = $folder;

    // Check if passwords match
    if ($password !== $cpassword) {
        echo 'Passwords do not match.';
        exit();
    }

    // Check if email already exists
    $existsql = "SELECT * FROM `login` WHERE number = ?";
    if ($stmt = mysqli_prepare($conn, $existsql)) {
        mysqli_stmt_bind_param($stmt, "s", $email1);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo 'Your email or number is already registered.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $sql = "INSERT INTO `login` (`fastname`, `lastname`, `number`, `password`, `img`) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Correct the bind_param call to match the number of placeholders
                mysqli_stmt_bind_param($stmt, "sssss", $fastname, $lastname, $email1, $hashed_password, $image_path);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    // Registration successful, start session
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $email1;
                    $_SESSION['id'] = mysqli_insert_id($conn);

                    // Redirect to index.php or any other appropriate page
                    header('Location: index.php?id=' . $_SESSION['id']);
                    exit();
                } else {
                    echo 'Registration failed. Please try again.';
                }
            } else {
                echo 'Error preparing the SQL statement: ' . mysqli_error($conn);
            }
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo 'Error preparing the SQL statement: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
