<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>


    <!-- Navigation -->

<?php include "includes/navigation.php"; ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php


// HOW TO UNDERSTAND PAGINATION!  SO IN THE (ISSET GET PAGE) YOU ARE GETTING THE NUMBER THAT YOU HAVE CLICKED ON THE PAGINATION, THE FIRST PART ITS EASY TO UNDERSTAND, BUT ON THE SECOND IF!!! IF ITS NOT EQUAL TO 0 YOU HAVE TO DO THE MATHEMATICS @@@ SO $page ITS EQUAL TO 3 FOR EXAMPLE! TIMES PERPAGE WHICH IS 5 ITS EQUAL TO 15 MINUS 5 ITS EQUAL TO 10!! THAT RESULT GOES TO THE QUERY WHERE THE KEY LIMIT IS!!! AND THE FIRST VALUE IN LIMIT IS THE NUMBER FROM WHEN IT WILL START COUNTING SO FROM NUMBER 10 IN THE DATABASE SHOW 5, THOSE ARE THE TWO VARIABLES THAT NEED. AND THATS HOW ON PAGE NUMBER 3 WILL SHOW FROM THE 10 POST ON THE DATABASE WILL BE SHOWING 5 


            $per_page = 5;

            if(isset($_GET['page'])){

                $page = $_GET['page'];

            } else {
                $page = "";
            }

            if($page == "" || $page == 1) {
                
                $page_1 = 0;

            } else {
                        // @@@
                $page_1 = ($page * $per_page) - $per_page;

            }


            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin') {

            $post_query_count = "SELECT * FROM posts";

            } else {

                $post_query_count = "SELECT * FROM posts WHERE post_status = 'Published' "; 
            }
                
            $find_count = mysqli_query($connection, $post_query_count);
            $count = mysqli_num_rows($find_count);

            if($count < 1) {
                echo "<h1 class='text-center'>NO POSTS AVAILABLE</h1>";
            } else {

            $count = ceil($count / $per_page);

            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin') {

                $query = "SELECT * FROM posts LIMIT $page_1, $per_page ";
    
                } else {
    
                    $query = "SELECT * FROM posts WHERE post_status = 'Published' LIMIT $page_1, $per_page"; 
                }
            

            // $query = "SELECT * FROM posts WHERE post_status = 'Published' LIMIT $page_1, $per_page ";
            $select_all_posts_query = mysqli_query($connection, $query);

             while($row = mysqli_fetch_assoc($select_all_posts_query)){
             $post_id = $row['post_id'];
             $post_title = $row['post_title'];
             $post_author = $row['post_user'];
             $post_date = $row['post_date'];
             $post_image = $row['post_image'];
             $post_content = substr($row['post_content'],0,100);
             $post_status = $row['post_status'];
    
             ?>

<h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- First Blog Post -->

                
                <h2>
                    <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title ?></a>
                </h2>
                <p class="lead">
                    by <a href="author_post.php?author=<?php echo $post_author ?>&p_id=<?php echo $post_id ?>"><?php echo $post_author ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                <hr>
                <a href="post.php?p_id=<?php echo $post_id ?>"><img class="img-responsive"  src="images/<?php echo imagePlaceHolder($post_image); ?>"  alt=""> </a>
                <hr>
                <p><?php echo $post_content ?></p>
                <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
              

                <hr>
        
             <?php } } ?>

        
            </div>

            <!-- Blog Sidebar Widgets Column -->

<?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <ul class = "pager">

        <?php 
        
        for($i = 1; $i <= $count; $i++){

            if($i == $page){
                echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";


            } else {

            echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";

            }
        }

        ?>
        
        </ul>

<?php include "includes/footer.php";?>

