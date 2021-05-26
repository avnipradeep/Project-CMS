 <?php include "db.php"; ?>
<!-- <?php  include "includes/header.php"; ?> -->

<?php

if(isset($_POST['login']))
{  

     $username = $_POST['username'];
    $user_password=$_POST['password'];

    if(empty($username) && empty($password))
    {
        header("location:../index.php");
    }

    //SQL INJECTION
    $username=mysqli_real_escape_string($connection,$username);
    $user_password=mysqli_real_escape_string($connection,$user_password);



    //SELECTING THE USER
    $query="SELECT * FROM users WHERE username='{$username}'";
    $select_user_query= mysqli_query($connection,$query);

    if(!$select_user_query)
    {
        die("QUERY FAILED".mysqli_error($connection));

    }

//FETCHING THE RECORD OF THE USER

    while($row=mysqli_fetch_assoc($select_user_query))
    {
        
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
        
    }

      
    $cryptpassword=crypt($user_password,$db_user_password);
   

    //CHECKING LOGIN DETAILS
     
    if($username === $db_username && $cryptpassword === $db_user_password)
    {
        session_start();
        $_SESSION['username']= $db_username;
        $_SESSION['user_firstname']= $db_user_firstname;
        $_SESSION['user_lastname']= $db_user_lastname;
        $_SESSION['user_role']= $db_user_role;




        header("Location:../admin/index.php"); //After that at header we are checking the user role in header of admin
    }
    else
    {
      header("Location: ../index.php");
 

    }
}
