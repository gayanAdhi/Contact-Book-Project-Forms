<?php
//start the session
session_start();

//check if the user is logged in
if (isset($_SESSION['user_id'])) 
{
    echo $_SESSION['name'];
} 
else {
    header('Location: login.php');
    exit();
}

require_once 'db_connect.php';

//add a new group
if (isset($_POST['submit'])) {
    
    $name = $_POST['group-name'];
    $user_id = $_SESSION['user_id'];

    
    $stmt = $conn->prepare('INSERT INTO `_group_`(`name`, `user_id`) VALUES (:name, :user_id)');
    $stmt->execute(array(':name' => $name, ':user_id' => $user_id));

    try
    {
        if($stmt->execute(array(':name'=>$name, ':user_id' => $user_id)))
        {
            $_SESSION['success']="new group added";
            header('Location: add-group.php');
             exit();
        }
        else{
            $_SESSION['error']="group not added";
            header('Location: add-group.php');
            exit();
        }

    }
    catch(PDOException $ex)
{
    $_SESSION['error']="group not added - " .$ex->getMessage();
}
   
    

    header('Location: add-group.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="form-style.css">
    <title>Add Group</title>
</head>
<body>

      
    <h1>Add Group</h1>
    <form method="post" action="#">
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
        <label for="group-name">Group Name:</label>
        <input type="text" name="group-name" id="group-name" required>
        <input type="submit" name="submit" value="Add Group">
    </form>
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
