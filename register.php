<?php
//Include the database connection file
require_once "db_connect.php";
    session_start();
//check if the form was submitted
if(isset($_POST['submit'])){

   

    //Sanitize the user inputs
    $fullname=filter_var($_POST['fullname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password=filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //check if the email already exists in the database
    $email_check_query="SELECT * FROM `user` WHERE `email` = :email";
    $stmt = $conn->prepare($email_check_query);
    $stmt->bindParam(':email',$email);
    $stmt->execute(); // Corrected from $stmt.execute()

    if($stmt->rowCount() > 0){
        header('Location:register.php');
        $_SESSION['error']="This Email_Address already exists ";
        exit();
    }
    //error If the password is less than 8 characters
    if (strlen($password) <8){
        header('Location:register.php');
        $_SESSION['error']="Password must be at least 8 character long";
        exit();
    }
   // unset($_SESSION['error']);

    //Hash the password using bcrypt algorithm
    $password=password_hash($password, PASSWORD_BCRYPT);

    //prepare an sql statement for inserting user data into the database
    $stmt=$conn->prepare("INSERT INTO `user`( `fullname`, `email`, `password`) VALUES (:fullname,:email,:password)");

    //Bind the sanitized user inputs to the sql statement
    $stmt->bindParam(':fullname',$fullname);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':password',$password);

    try
    {
        //Execute the sql statement to insert user data into the database
       if( $stmt->execute())
       {
        $_SESSION['success']='account create successfully';
        header('Location: register.php');
        exit();
       }
    else{
        $_SESSION['error']='account not created';
        exit();
    }
}
    catch(PDOException $ex)
    {
        $_SESSION['error']='account not created -' .$ex->getMessage();
    }
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    

    //close the database connection
    $conn=null;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name ="viewport" content="width=device-width,initial-scale=1.0">
        <link rel ="stylesheet" type="text/css" href="lg_rg_style.css">
        <title>Sign Up Form</title>
    </head>
    <body>
       <div class ="signup-wrapper">


      
            <form action ="<?php echo $_SERVER['PHP_SELF']?>" method="post" class ="signup-form">

 <?php 
            if(isset($_SESSION['error'])){
    echo '<div class ="alert error"><p>'.$_SESSION['error'].'</p><span class="close">
    &times;</span></div>' ;
}
elseif(isset($_SESSION['success']))
{
    echo '<div class ="alert success"><p>'.$_SESSION['success'].'</p><span class="close">
    &times;</span></div>' ;
}


       unset($_SESSION['error']);
       unset($_SESSION['success']);
?>
            


      
                <h1>Sign Up</h1>
                <div class ="form-group">
                    <label for ="name">Fullname:</label>
                    <input type="text" id="name" name="fullname" required>
                </div>
                <div class ="form-group">
                    <label for ="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class ="form-group">
                    <label for ="name">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class ="form-group">
                    <button type="submit" name="submit">SignUp</button>
                </div>
                <p> Already have an account?<a href ="login.php">login</a></p>
            </form>
        </div>

        <script>
            //close the alert message
            document.querySelectorAll(".close").forEach(function(closeButton){
                closeButton.addEventListener("click",function(){
                    closeButton.parentElement.style.display = "none";
                });
            });
        </script>
    </body>
</html>
