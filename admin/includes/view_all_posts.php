<?php
 include("delete_modal.php");

 if(isset($_POST['checkBoxArray'])){

    foreach($_POST['checkBoxArray'] as $postValueId){

     $bulk_options = $_POST['bulk_options'];

         switch($bulk_options){
            case 'Published':
                 $query = "UPDATE posts set post_status = '{$bulk_options}' WHERE post_id = {$postValueId}  " ;
                 $update_to_publish_status = mysqli_query($connection, $query);
                 confirm($update_to_publish_status);

                 break;

            

            case 'draft':
                $query = "UPDATE posts set post_status = '{$bulk_options}' WHERE post_id = {$postValueId}  " ;
                $update_to_draft_status = mysqli_query($connection, $query);
                confirm($update_to_draft_status);

                break;


            case 'delete':
                $query = "DELETE FROM posts WHERE post_id = {$postValueId}  " ;
                $update_to_delete = mysqli_query($connection, $query);
                confirm($update_to_delete);

                break;


            case 'clone': 
            
         $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
         $select_post_query = mysqli_query($connection, $query);

         while ($row = mysqli_fetch_array($select_post_query)) {
             $post_title = $row['post_title'];
             $post_category_id = $row['post_category_id'];
             $post_date = $row['post_date'];
             $post_author = $row['post_author'];
             $post_status = $row['post_status'];
             $post_image = $row['post_image'];
             $post_tags = $row['post_tags'];
             $post_content = $row['post_content'];

         }

         $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";

         $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}') ";

         $clone_query = mysqli_query($connection, $query);
         confirm($clone_query);

         break;

           }    

        }
      }

    


 

?>

<form action="" method="post">

<table class= "table table-bordered table-hover">

<div id="bulkOptionsContainer" class="col-xs-4">
    <select class="form-control" name="bulk_options" id="">  
        <option value="">Select Options</option>
        <option value="Published">Publish</option>
        <option value="draft">Draft</option>
        <option value="delete">Delete</option>
        <option value="clone">Clone</option>
    </select>
    
</div>


    <div class="col-xs-4">

    <input type="submit" name="submit" class="btn btn-success" value="Apply">
    <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        
    </div>



                            <thead>
                                <tr>
                                    <th><input id="selectAllBoxes" type="checkbox"></th>
                                    <th>Id</th>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>Categories</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Tags</th>
                                    <th>Views</th>
                                    <th>comments</th>
                                    <th>date</th>
                                    <th>View post</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

<?php

$query = "SELECT * FROM posts ORDER BY post_id DESC ";
$select_post = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_post)){
    $post_id = $row['post_id'];
    $post_author = $row['post_author']; 
    $post_user = $row['post_user']; 
    $post_title = $row['post_title']; 
    $post_category_id = $row['post_category_id']; 
    $post_status = $row['post_status']; 
    $post_image = $row['post_image']; 
    $post_tags = $row['post_tags']; 
    $post_comment_count = $row['post_comment_count']; 
    $post_date = $row['post_date']; 
    $post_views = $row['post_views_count'];

echo "<tr>";

?>

<td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>

<?php
echo "<td>{$post_id}</td>";

if(!empty($post_author)){

echo "<td>{$post_author}</td>";
} else if(!empty($post_user)) {

echo "<td>{$post_user}</td>";

}

echo "<td>{$post_title}</td>";


$query = "SELECT * FROM categories WHERE cat_id = $post_category_id";
$select_categories_id = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_categories_id)){
$cat_id = $row['cat_id'];
$cat_title = $row['cat_title'];

echo "<td>{$cat_title}</td>";

}


echo "<td>{$post_status}</td>";
echo "<td><img width ='100' src = '../images/{$post_image}' alt='image'></td>";
echo "<td>{$post_tags}</td>";
echo "<td>{$post_views} <a href='posts.php?reset={$post_id}'>Reset</a></td>";

$query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
$comment_query = mysqli_query($connection, $query);
$row = mysqli_fetch_array($comment_query);
$comment_id = $row['comment_id'];
$count_comment = mysqli_num_rows($comment_query);

echo "<td><a href='post_comments.php?id=$post_id'>{$count_comment}</a></td>";




echo "<td>{$post_date}</td>";
echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
// echo "<td><a onClick=\"javacript: return confirm('are you sure you want to delete?');\" href='posts.php?delete={$post_id}'>Delete</a></td>";
?>

<form method="post">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php
   echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
    ?>
</form>
<?php

// echo "<td><a rel='$post_id' href='javascript:void(0)'class='delete_link'>Delete</a></td>";



echo "</tr>";

}

?>
                            
                                
                            </tbody>
                        </table>

                        </form>

<?php
if(isset($_POST['delete'])){

    $the_post_id = escape($_POST['post_id']);

    $query= "DELETE FROM posts WHERE post_id = {$the_post_id} ";
    $delete_query =  mysqli_query($connection, $query);
    header("Location: posts.php");
}

if(isset($_GET['reset'])){

    $the_post_id = $_GET['reset'];

    $query="UPDATE posts SET post_views_count = 0 WHERE post_id=" . mysqli_real_escape_string($connection, $_GET['reset']) . "";
    $reset_query = mysqli_query($connection,$query);
    header("location:posts.php");
}

?>

<script>

$(document).ready(function(){

   $(".delete_link").on('click', function(){

    var id = $(this).attr("rel");
    var delete_url = "posts.php?delete="+ id +" ";

    $(".modal_delete_link").attr("href", delete_url);

    $("#exampleModalCenter").modal('show');
    
   });

});

</script>