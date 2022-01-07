<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

    <!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<?php 

if(isset($_POST['liked'])){
   
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    // SELECT POST

    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $searchPostQuery = mysqli_query($connection, $query);
    $result = mysqli_fetch_Array($searchPostQuery);
    $likes = $result['likes'];
   

    // 2) UPDATE POST WITH LIKES
  
        $query = "UPDATE posts SET likes = $likes + 1 WHERE post_id = $post_id";
        $update_likes = mysqli_query($connection, $query);
        confirm($update_likes);

    // 3) CREATE LIKES FOR POST

        mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();
}


if(isset($_POST['unliked'])){


   
    // // SELECT POST
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // FETCHING THE POST

    $query = "SELECT * FROM posts WHERE post_id = $post_id";
    $searchPostQuery = mysqli_query($connection, $query);
    $result = mysqli_fetch_Array($searchPostQuery);
    $likes = $result['likes'];
   
    // 2) DELETE LIKES

    $query = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    $update_likes = mysqli_query($connection, $query);
    confirm($update_likes);


    // 3)UPDATE DECREMENT LIKES 

    $query = "UPDATE posts SET likes = $likes - 1 WHERE post_id = $post_id";
    $update_likes = mysqli_query($connection, $query);
    confirm($update_likes);

}


?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php

          if(isset($_GET['p_id'])){
          
            $the_post_id = $_GET['p_id'];

            $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id";
            $send_query= mysqli_query($connection, $view_query);
            if(!$view_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }

            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin'){

                $query = "SELECT * FROM posts WHERE post_id = $the_post_id";

            } else {

                $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'Published' ";

            }

            $select_all_posts_query = mysqli_query($connection, $query);


            if(mysqli_num_rows($select_all_posts_query) < 1){

                echo "<h1 class= 'text-center'>No posts available</h1>";

            } else {

             while($row = mysqli_fetch_assoc($select_all_posts_query)){
             $post_title = $row['post_title'];
             $post_author = $row['post_author'];
             $post_date = $row['post_date'];
             $post_image = $row['post_image'];
             $post_content = $row['post_content'];
             $post_views = $row['post_views_count'];
             $likes = $row['likes'];
    
             ?>

                <h1 class="page-header">
                         Posts
            
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="#"><?php echo $post_title; ?></a>
                </h2>
                <p class="lead">
                    by <a href="index.php"><?php echo $post_author ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo imagePlaceHolder($post_image); ?>" alt="">
                <hr>
                <p><?php echo $post_content ?></p>

                <!-- freeing result -->
                <!-- <?php mysqli_stmt_free_result($stmt); ?> -->

                <button type="button" class="btn btn-primary">VIEWS : <?php echo $post_views; ?></button>

                <?php if(isLoggedIn()):?>

                <div class="row">

                <p class='pull-right btn btn-primary'> Likes  <?php echo getPostLikes($the_post_id); ?> </p>
                  
             
                <p class='pull-right'><a class='<?php echo userLikedPost($the_post_id)? 'unlike': 'like'; ?>' href=""><span class= '<?php echo userLikedPost($the_post_id)? "glyphicon glyphicon-thumbs-down" : "glyphicon glyphicon-thumbs-up";?>'></span>Like Post </a> </p>
                
                </div> 

                    <hr> 
                
             <?php else: ?>

             <div class="row">

             <p class='btn btn-primary pull-right'>Likes <?php echo getPostLikes($the_post_id); ?></p>
            
             
             <p class='pull-right'> You need to <a href="login.php"> log in</a> to like Posts.</p>  
             </div>
             <hr>
             
             
          
            

            <?php endif ?>
            
            
             <?php } ?>

                <!-- Blog Comments -->

                <?php

                if(isset($_POST['create_comment'])) {
                
                $the_post_id = $_GET['p_id'];

                $comment_author = $_POST['comment_author'];
                $comment_email = $_POST['comment_email'];
                $comment_content = $_POST['comment_content'];
                
                // CHECKING FOR EMPTY FIELDS ON COMMENTS
                if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                // 
                $query= "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";

                $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}','{$comment_content}', 'unapproved',now() ) ";

                $create_comment_query = mysqli_query($connection, $query);

                if(!$create_comment_query){

                    die('QUERY FAILED'. mysqli_error($connection));

                }

                


                // THIS IS A WAY TO INCREMENT THE COMMENT COUNT ON A WEB PAGE 

                // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                // $query .= "WHERE post_id = $the_post_id ";
                // $update_comment_count = mysqli_query($connection, $query);

                    } else {

                        echo "<script>alert('this fields should not be empty')</script>";

                    }
                }
            }

                ?>

                <!-- Comments Form -->
                <div class="well">

                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">

                    <div class="form-group">
                           <label for="Author"> Author</label>
                           <input type="text" class= "form-control" name="comment_author">
                        </div>
                        <div class="form-group">
                           <label for="Email">Email</label>
                           <input type="email" class= "form-control" name="comment_email">
                        </div>

                        <div class="form-group">
                        <label for="comment">Your Comment</label>
                            <textarea name= "comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name= "create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php
                $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} " ;
                $query .= "AND comment_status = 'approved' ";
                $query .= "ORDER BY comment_id DESC";
                $select_comment_query = mysqli_query($connection, $query);
                if(!$select_comment_query){

                    die("QUERY FAILED" . mysqli_error($connection));
                }

                while($row = mysqli_fetch_assoc($select_comment_query)) {
                    $comment_date = $row['comment_date'];
                    $comment_content = $row['comment_content'];
                    $comment_author = $row['comment_author'];
                

                ?>

                <!-- Comment -->
             <div class="media">
            <a class="pull-left" href="#">
            <img class="media-object" src="http://placehold.it/64x64" alt="">
        </a>
        <div class="media-body">
            <h4 class="media-heading"><?php echo $comment_author; ?>
                <small><?php echo $comment_date; ?></small>
            </h4>
            <?php echo $comment_content; ?>
        </div>
    </div>



            <?php  } } else {

               header("location:index.php"); 
            }  
            
                ?>



            </div>
           
            <!-- Blog Sidebar Widgets Column -->

<?php include "includes/sidebar.php"; ?>

</div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php";?>

<script>

$(document).ready(function(){

    var post_id = <?php echo $the_post_id; ?>;
    var user_id = <?php echo loggedInUserId(); ?>;

// liking
    $('.like').click(function(){

        $.ajax({
            url:"/post.php?p_id=<?php echo $the_post_id; ?>",

            type:'post',

            data: {
                liked: 1,
                post_id: post_id,
                user_id: user_id
            }
        });
    });

    // Unliking
    $('.unlike').click(function(){

        $.ajax({
            url:"/post.php?p_id=<?php echo $the_post_id; ?>",

            type:'post',

            data: {
                    unliked: 1,
                    post_id: post_id,
                    user_id: user_id
                    }                   
                });
        });
});

</script>