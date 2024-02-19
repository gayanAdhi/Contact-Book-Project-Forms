<?php

//start the session
session_start();

//check if the user is logged in
if(isset($_SESSION['user_id']))
{
    echo $_SESSION['name'];
}
//if not redirect to the login page
else
{
    header('Location: login.php');
    exit();
}
require_once "db_connect.php";

$user_id=$_SESSION['user_id'];

//get the user groups
//Query to retrive group names
$sql= "SELECT * FROM `_group_` WHERE 'user_id'=:user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id',$user_id);
$stmt->execute();



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name ="viewport" content="width=device-width,initial-scale=1.0">
        <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel ="stylesheet" type="text/css" href="contacts-style.css">
        <title>My Groups</title>
    </head>
    <body>
<div class ="container">
    <nav class ="menu">
        <ul>
             <li><a href="groups.php">Groups</a></li>
            <li><a href ="contacts.php">Contacts</a></li>
            <li><a href ="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h1>My Groups</h1>
    <!-- <div class ="search">
        <form action ="#" method="post">
            <input type="search" name ="search" placeholder="search contacts...">
            <button type="submit" name="search-submit"> search</button>
        </form>
    </div>-->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Group Name</th>
            <th>Action</th>
            <th></th>
            
        </tr>
    </thead>
    <tbody>
       
           <!--  <td>test name </td>
            <td>
                <a href ="#" class ="icon icon-edit" title="edit"></a>
                <a href ="#" class ="icon icon-delect" title="delect"></a>
            </td>
        </tr> -->
        <?php
//Generate table rows

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>";
    echo "<a href='#' class='icon icon-edit' title='edit'></a>"; 
    echo "<a href='#' class='icon icon-delect' title='delect'></a>"; 
    echo "</td>";
    echo "</tr>";
}

        ?>
    </tbody>
</table>
<div class ="icon-add-container">
    <a href="add-group.php" class="icon icon-add" title="add group">
        <i class ="fas fa-plus"></i>
    </a>
</div>
</div>
</div>
</body>
</html>