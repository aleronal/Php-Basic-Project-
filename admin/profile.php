<?php include "includes/admin_header.php"; ?>

<div id="wrapper">
<div id="page-wrapper">
<?php 

if(isset($_SESSION['username'])){

    $username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username = '{$username}'";

$select_user_profile_query = mysqli_query($connection, $query);

while($row = mysqli_fetch_array($select_user_profile_query)){

    $user_id = $row['user_id'];
    $username = $row['username']; 
    $user_password = $row['user_password']; 
    $user_firstname = $row['user_firstname']; 
    $user_lastname = $row['user_lastname']; 
    $user_email = $row['user_email']; 
    $user_image = $row['user_image']; 
    $user_role = $row['user_role']; 

    }
}
?>

<?php  
if(isset($_POST['edit_user'])){

    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    // $post_image= $_FILES['image'] ['name'];
    // $post_image_temp= $_FILES['image'] ['tmp_name'];

    $username = $_POST['username'];
    $user_email= $_POST['user_email'];
    $user_password= $_POST['user_password'];
    
    if(empty($user_password)){
        $warning_message = "<p class='text-danger'>Password Field cant be empty</p>";
      } else {

    $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10)); 
  

    // $post_date = date('d-m-y');
    // $post_comment_count = 4;

    
    // move_uploaded_file($post_image_temp, "../images/$post_image") ;

  $query = "UPDATE users SET ";
  $query .= "user_firstname = '{$user_firstname}', ";
  $query .= "user_lastname = '{$user_lastname}', ";
  $query .= "user_role = '{$user_role}', ";
  $query .= "username = '{$username}', ";
  $query .= "user_email = '{$user_email}', ";
  $query .= "user_password = '{$user_password}' ";
  $query .= "WHERE username = '{$username}'" ;

  $edit_user_query = mysqli_query($connection, $query);
  confirm($edit_user_query);

  echo "<h1 class='bg-success'>User Updated</h1>"; 
  // WE HAVE TO FIX THIS THE CLASS IS NOT GETTING SET UP ON TOP WHERE IT SHOULD BE!!!!!!!!!!!!!!!!
  
    }
}

?>

        <!-- Navigation -->
<?php include "includes/admin_navigation.php"; ?>

  <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                    <h1 class="page-header">
                            WELCOME TO Admin
                            <small>Author</small>
                    </h1>

<form action = "" method = "post" enctype="multipart/form-data">

<div class="form-group">
    <label for="title">First Name </label>
    <input type="text" value ="<?php echo $user_firstname; ?> "class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
    <label for="post_status">Last Name</label>
    <input type="text" value ="<?php echo $user_lastname; ?> " class="form-control" name="user_lastname">
    </div>

    <div class = "form-group">
    
    <select name="user_role" id="">
    <option value="subcriber"><?php echo $user_role ?></option>
    
    </select>
    
    </div>

   

   
    <!-- <div class="form-group">
    <label for="post_image">Post image</label>
    <input type="file" name="image">
    </div> -->


    <div class="form-group">
    <label for="post_tags">User Name</label>
    <input type="text" value ="<?php echo $username; ?> " class= "form-control" name="username">
    </div>

    
    <div class="form-group">
    <label for="post_content">Email</label>
    <input type="email" value ="<?php echo $user_email; ?> " class= "form-control" name="user_email">
    </div>

    <div class="form-group">
    <label for="post_content">Password <?php echo $warning_message; ?></label>
    <input autocomplete="off" type="password"  class= "form-control" name="user_password">
    </div>

    <div class="form-group">
    <input class= "btn btn-primary" type="submit" name="edit_user" value="Update Profile">
    </div>

</form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

       </div> 
        <!-- /#page-wrapper --> 
</div>
<?php include "includes/admin_footer.php" ;?>