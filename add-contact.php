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
$user_id = $_SESSION['user_id'];

//get the user groups
//Query to retrive group names
$sql= "SELECT * FROM `_group_` WHERE 'user_id'=:user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id',$user_id);
$stmt->execute();

//Generate options for select element
$options='';

while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
    $options .='<option value="' .$row['id']. '" name="group_id">'
                    .$row['name'].'</option>';
}


//add a new contact
if (isset($_POST['submit'])) {
    
   $name = $_POST['name'];
   $email = $_POST['email'];
   $phone= $_POST['phone'];
   $address = $_POST['address'];
   $group_id = $_POST['group'];
   //$user_id = $_SESSION['user_id'];

    
    $stmt = $conn->prepare('INSERT INTO `_contact_`( `name`, `email`, `phone`, `address`, `group_id`, `user_id`) VALUES (:name,:email, :phone,:address,:group_id,:user_id)');
    
    try
    {
        if($stmt->execute(array(':name'=>$name, ':email' => $email, ':phone' => $phone, ':address' => $address , ':group_id' => $group_id, ':user_id' => $user_id)))

        {
            $_SESSION['success']="new contact added";
            header('Location: add-group.php');
             exit();
        }
        else{
            $_SESSION['error']="contact not added";
            header('Location: add-contact.php');
            exit();
        }

    }
    catch(PDOException $ex)
{
    $_SESSION['error']="group not added - " .$ex->getMessage();
}
   
    
    unset($_SESSION['sucess']);
    unset($_SESSION['error']);

    header('Location: add-contact.php');
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name ="viewport" content="width=device-width,initial-scale=1.0">
        <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel ="stylesheet" type="text/css" href="form-style.css">
        <title>Add Contact</title>
    </head>
    <body>
    
    <h1>Add Contact</h1>
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
        <label for="name"> Name:</label>
        <input type="text" name ="name"  id="name" required>

        <label for="email"> Email:</label>
        <input type="email" name ="email"  id="email"  required>

        <label for="phone"> Phone:</label>
        <input type="text" name ="phone"  id="phone" required>

        <label for="address"> Address:</label>
        <textarea name ="address" id="address" rows ="4" required></textarea>

        <label for="group"> Group:</label>

        <!--Html select element with generated options -->
        <select name="_group_" id="_group_">
            <option value="">--select a Group--</option>
            <!-- populate options dynamically from the database -->
            <?php  echo $options; ?>
        </select>
        <input type="submit" name="submit" value="Add Contact">
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