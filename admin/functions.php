<?php

function escape($string){
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
    }

function users_online() {

  
    if(isset($_GET['onlineusers'])){

    global $connection;

    if(!$connection) {

        session_start();

        include("../includes/db.php");


$session = session_id();
$time = time();
$time_out_in_seconds = 60;
$time_out = $time - $time_out_in_seconds ; 

$query = "SELECT * FROM users_online WHERE session = '$session' ";
$send_query = mysqli_query($connection, $query);
$count = mysqli_num_rows($send_query);

if($count == NULL) {

    mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");

} else {

    mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");

}

    $users_online_query = mysqli_query($connection,"SELECT * FROM users_online WHERE time > '$time_out'");
    echo $count_user = mysqli_num_rows($users_online_query);

    }

}//get request isset()

}
users_online();


function confirm($result) {

global $connection;

if(!$result){
    die ("QUERY FAILED" . mysqli_error($connection));   
  }
}


function insert_categories (){

    global $connection;
     
    if(isset($_POST['submit'])){
        $cat_title = $_POST['cat_title'];
        
        if($cat_title == "" || empty($cat_title)){

            echo "This field should not be empty";


        }else {

            $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES (?) ");

            mysqli_stmt_bind_param($stmt, 's', $cat_title);

            mysqli_stmt_execute($stmt);
            

            if(!$stmt) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }

        }
}


function findAllCategories(){
global $connection;

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categories)){
    $cat_id = $row['cat_id'];
    $cat_title = $row['cat_title'];

    echo "<tr>";
    echo "<td>$cat_id </td>";
    echo "<td>$cat_title</td>";
    echo "<td><a class='btn btn-danger'href='categories.php?delete={$cat_id}'>Delete</a></td>";
    echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
    echo "</tr>";
  }
}



function delete_categories(){
global $connection;

    if(isset($_GET['delete'])){
    $the_cat_id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
    $delete_query = mysqli_query($connection,$query);
    header("location: categories.php");
 }
}

function is_admin($username){
    global $connection;

    $query = "SELECT user_role FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirm($result);

    $row = mysqli_fetch_array($result);
    if($row['user_role'] =='Admin'){
        return true;
    }else {
        return false;
    }
}

function username_exist($username){

    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirm($result);

    if(mysqli_num_rows($result) > 0 ){
        return true;
    }else{
        return false;
    }

}

function redirect($location){

    header("location:" . $location);
    exit;
}


function ifItIsMethod($method=null){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }

    return false;

}

function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
    return true;
}

    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){

    if(isLoggedIn()) {
        redirect($redirectLocation);

    }   
}

function logInUser($username, $password){

global $connection;

    // $username= trim($username);
    // $password= trim($password);

    $username = mysqli_real_escape_string($connection, $username );
    $password= mysqli_real_escape_string($connection, $password );

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if(!$select_user_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }

    while($row = mysqli_fetch_array($select_user_query)){
    $db_user_id = $row['user_id'];
    $db_username = $row['username'];
    $db_user_password = $row['user_password'];
    $db_user_firstname = $row['user_firtsname'];
    $db_user_lastname = $row['user_lastname'];
    $db_user_role = $row['user_role'];


        if(password_verify($password, $db_user_password)) {


            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;

            header("location: ../admin/index.php");
        
        } else{

            return false;

        }

    }
}


function username_exists($username){
    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    confirm($result);

  if(mysqli_num_rows($result) > 0){
      return true;
  } else {
      return false;
  }
}

function email_exists($email){
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection, $query);
    confirm($result);

  if(mysqli_num_rows($result) > 0){
      return true;
  } else {
      return false;
  }
}

// this function is to know which user is loggend in or if you want to just show posts or just to show somethig related to the current user
function currentUser(){
    if(isset($_SESSION['username'])){

        return $_SESSION['username'];

    }else{
        return false;
    }
    
}

function imagePlaceHolder($image=''){
    if(!$image){
        return 'noimage.jpg';
    }else{
        return $image;
    }
}

function query($query){
    global $connection;

    return mysqli_query($connection, $query);
}

function loggedInUserId(){

    if(isLoggedIn()){

        $result= query("SELECT * FROM users WHERE username='".$_SESSION['username'] ."'");
        confirm($result);
        $user = mysqli_fetch_array($result);
        if(mysqli_num_rows($result) >= 1 ){
            return $user['user_id'];
        }

    }

    return false;
    
}

function userLikedPost($post_id=''){
$result = query("SELECT * FROM likes WHERE user_id=". LoggedInUserId(). " AND post_id = {$post_id}");
return mysqli_num_rows($result) >= 1 ? true : false;
}

function getPostLikes($post_id=''){
        global $connection;
    $query = "SELECT * FROM likes WHERE post_id = $post_id";
    $result = mysqli_query($connection,$query);
    echo mysqli_num_rows($result);
    
}


?>

