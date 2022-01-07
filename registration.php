<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>

<?php 

// SETTING LANGUAGE VARIABLES

if(isset($_GET['lang']) && !empty($_GET['lang'])){

    $_SESSION['lang'] = $_GET['lang'];

    if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']){

        echo "<script type='text/javascript'>location.reload();</script>></script>";

    }
} 

if(isset($_SESSION['lang'])){
    include "includes/languages/" .$_SESSION['lang'].".php";

} else {

    include "includes/languages/en.php";

}









// CODE FOR SENDING DATA TO REGRISTRATE USER

if($_SERVER['REQUEST_METHOD']== "POST"){

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $username = mysqli_real_escape_string($connection, $username);
    $email    = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    $error= [
        'username'=>'',
        'email' => '',
        'password'=>'',
    ];

    if(strlen($username) < 4){
        $error['username']='Username needs to be longer';
    }
    if($username == ''){
        $error['username']='Username cannot be empty';
    }
    if(username_exists($username)){
        $error['username'] = 'Username alreade exists, pick another one';
    }
    if($email == ''){
        $error['email']='Email cannot be empty';
    }
    if(email_exists($email)){
        $error['email'] = 'Email alreade exists,<a href= "index.php">please login</a>"';
    }
    if($password == ''){
        $error['password'] =  'Password cant be empty';
    }

    foreach($error as $key => $value){
        if(empty($value)){
            unset($error[$key]);
        }

        if(empty($error)){
            
        

        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query .= "VALUES ('{$username}','{$email}','{$password}', 'subscriber') ";
    
        $register_user_query = mysqli_query($connection, $query);
        if(!$register_user_query){
            die("QUERY FAILED " . mysqli_error($connection));
        }else {
            $message = "Your Registration has been submitted";
        }

    }

    }
   
}


//   $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

//   if(!empty($username) && !empty($email) && !empty($password)) {

//         if(username_exists($username)){
//             $message2 = "this username is already taken";
//         }else if(email_exists($email)){
//             $message3 = "this email is already taken";
//         } else {
 
//     $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
//     $query .= "VALUES ('{$username}','{$email}','{$password}', 'subscriber') ";

//     $register_user_query = mysqli_query($connection, $query);
//     if(!$register_user_query){
//         die("QUERY FAILED " . mysqli_error($connection));
//     }

//     $message = "Your Registration has been submitted";

//      } } else { 
//        $message = "this fields can't be empty";
//     } 
// }  else {

//     $message = "";
// }


?>

    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">

    <form method="get" class="navbar-form navbar-right" action="" id="language_form">
        <div class="form-group">
            <select name="lang" class="form-control" onchange="changeLanguage()" >
                <option value="en"<?php if(isset($_SESSION['lang']) && $_SESSION['lang']== 'en'){ echo "selected";} ?>>English</option>
                <option value="es" <?php if(isset($_SESSION['lang']) && $_SESSION['lang']== 'es'){ echo "selected";} ?>>Spanish</option>
             </select>
        </div>
    </form>
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1><?php echo _REGISTER ;?></h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <h5 class="text-success"><?php echo $message; ?></h5>
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _USERNAME;?>" autocomplete= "on" value= "<?php echo isset($username) ? $username : '' ?>"> 
                            <p class='text-danger'><?php echo isset($error['username'])? $error['username']: '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _EMAIL;?>" autocomplete="on" value="<?php echo isset($email)? $email : '' ?>">
                            <p class='text-danger'><?php echo isset($error['email'])? $error['email']: '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD;?>">
                            <p class='text-danger'><?php echo isset($error['password'])? $error['password']: '' ?></p>
                        </div>
                
                        <input type="submit" name="register" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php echo _REGISTER;?>">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


        <hr>

        <script>
        
        function changeLanguage(){

            document.getElementById('language_form').submit();
            
        }

        </script>



<?php include "includes/footer.php";?>
