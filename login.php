<?php
// Include the database connection file
require_once "db_connect.php";

session_start();

$error_msg = "";

// Check if the form was submitted
if (isset($_POST['submit'])) {

    // Sanitize the user inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Prepare an SQL statement for selecting user data from the database
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if (password_verify($password, $result['password'])) {
            session_start();
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['name'] = $result['fullname'];
            header("Location: contacts.php");
            exit();
        } else {
            $error_msg = "Incorrect email or password";
        }
    } else {
        $error_msg = "Incorrect email or password";
    }

    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="lg_rg_style.css">
    <title>Login Form</title>
</head>

<body>
    <div class="signup-wrapper">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="signup-form">
            <h1>Sign In</h1>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="name">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php echo "<p style='color:red; margin-top:10px; margin-bottom:10px;'>" . $error_msg . "</p>" ?>
            <div class="form-group">
                <button type="submit" name="submit">Login</button>
            </div>
            <p>Don't have an account yet?<a href="register.php">SignUp</a></p>
    </div>

    </form>
    </div>
    </form>
    </div>
</body>

</html>
