<?php 
require '../includes/common.php';
$name = mysqli_real_escape_string($con,filter_input(INPUT_POST,'name'));
$email_chk = filter_input(INPUT_POST,'email');
$regex_email = "/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";
if(!preg_match($regex_email,$email_chk)){
    header('location: signUp.php?error=Invalid Email');
}else{
    $email = mysqli_real_escape_string($con, $email_chk);
    $pass = filter_input(INPUT_POST,'pass');
    $query = "SELECT count(*) FROM users WHERE email = '$email'";
    $email_exist = $con->query($query) or die(include 'wentWrong.php');
    if($email_exist){
        header('location: signup.php?error=Email already exist');
        $query = "SELECT count(*) FROM users WHERE username = '$username'";
        $username_exist = $con->query($query);
        if($username_exist){
            header('location: signup.php?error=Username already exist');
        }else{
            if(strlen($pass)<7){
                    header('location: signup.php?error=Password Requirement Failed');
            }else{
                $password = mysqli_real_escape_string($con,md5($pass));
                $username = mysqli_real_escape_string($con,filter_input(INPUT_POST,'username'));
                if(strlen($username)<5){
                    header('location: signup.php?error=Username Requirement Failed');
                }else{
                    $insert_query = "INSERT INTO users(name,email, username, password) values('$name','$email','$username', '$password')";
                    $insert_result = mysqli_query($con, $insert_query) or die(include 'wentWrong.php');
                    session_start();
                    $_SESSION['email']=$email;
                    $_SESSION['id'] = mysqli_insert_id($con);
                    header('location: home.php');
                }
            }
        }
    }
}
?> 