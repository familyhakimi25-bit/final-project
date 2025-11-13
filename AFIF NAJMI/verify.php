<?php
@include 'connection.php';

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!empty($email) && !empty($password)){

        $sql = "SELECT * FROM `customer` WHERE email = '$email' limit 1";
        $result = mysqli_query($conn, $sql);

        if($result)
        {
            if($result && mysqli_num_rows($result) >0){
                // assoc is short for associative array
                $user_data = mysqli_fetch_assoc($result);
                // if user's information is found in databse
                if($user_data['password'] === $password)
                {
                    // $_SESSION['user_ID'] = $user_data['user_ID'];
                    header("Location: service.php?cust_id=$user_data[cust_id]");
                    die;
                }
            }
        }
        echo "<script>alert('Wrong Email or Password!')</script>";
    }

}
?>