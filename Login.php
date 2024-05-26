
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    // Get email and password from POST request
    $email2 = $_POST['logemail'];
    $password2 = $_POST['logpassword'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT * FROM login WHERE number = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "s", $email2);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Check if a user with the provided email exists
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify the password
            if (password_verify($password2, $row['password'])) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email2;
                $_SESSION['id'] = $row['id'];

                // Redirect to index.php with the user ID
                header('Location: index.php?id=' . $row['id']);
                exit();
            } else {
                echo 'Invalid password.';
            }
        } else {
            echo 'No user found with the provided email.';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo 'Error preparing the SQL statement: ' . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo 'Invalid request method.';
}
?>
