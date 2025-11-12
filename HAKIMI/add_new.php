<?php
include "db_conn.php";

if(isset($_POST['submit'])){
    
    $cust_name = $_POST['cust_name'];
    $cust_email = $_POST['cust_email'];
    $cust_notes = $_POST['cust_notes'];
    $cust_date = $_POST['cust_date'];
    

    $sql = "INSERT INTO admin (cust_id , cust_name , cust_email , cust_notes, cust_date)
     VALUES (NULL,'$cust_name','$cust_email','$cust_notes','$cust_date')";
    
     $result = mysqli_query($conn, $sql);

     if($result){
        header("Location: index.php?msg=New record created successfully");
     }
     else{
        echo "Failed:" . mysqli_error($conn);
     }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

     <!--Font Awesome-->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
     integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
     crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>KI'AD BARBERSHOP ADMIN SYSTEM</title>
</head>
<body>

<nav class="navbar navbar-light justify-content-center fs-3 mb-5"
style="background-color: #9DB2BF">
    KI'AD BARBERSHOP ADMIN SYSTEM
</nav>

<div class="container">
    <div class="text-center mb-4">
        <h3>Add New User</h3>
        <p class="text-muted">Complete the form below to add new user</p>
    </div>

    <div class="container d-flex justify-content-center">
        <form action="" method="post" style="width:50vw; min-width:300px;">
        <div class="row mb-3">
           
        </div>

        <div class="col mb-3">
                <label class="form-label">
                    Name:
                </label>
                <input type="text" class="form-control" name='cust_name' placeholder="Name">
            </div>

            <div class="col mb-3">
                <label class="form-label">
                    Email:
                </label>
                <input type="text" class="form-control" name='cust_email' placeholder="Name@gmail.com">
            </div>

            <div class="col mb-3">
                <label class="form-label">
                    Notes:
                </label>
                <input type="text" class="form-control" name='cust_notes' placeholder="Haircut">
            </div>



           <div class="col mb-3">
                <label class="form-label">
                    Date & Time:
                </label>
                <input type="datetime-local" class="form-control" name='cust_date'>
            </div>

            
            <div>
                <button type="submit mb-4" class="btn btn-success mb-4" name="submit">Save</button>
                <a href="index.php" class="btn btn-danger mb-4">Cancel</a>
            </div>
       </form>
    </div>
</div>
    <!--Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>