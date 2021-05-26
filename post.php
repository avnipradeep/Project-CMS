<?php include "includes/db.php";?>
<?php include "includes/header.php";?>

    <!-- Navigation -->
   
 <?php 
include "includes/navigation.php";
?>

<?php
 
 if(isset($_POST['liked'])){

   $post_id = $_POST['post_id'];
   $user_id = $_POST['user_id'];

   
   //1 = fetching the right post

$query = "SELECT * FROM posts WHERE post_id= $post_id";
$postResult = mysqli_query($connection, $query);
$post = mysqli_fetch_array($postResult);
$likes = $post['likes'];

// if(mysqli_num_rows($postResult)>= 1) {
//     echo $post['post_id'];
// }
//2 = update post with post

mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id = $post_id");

//3 = create likes for post

//$result = mysqli_query($connection, "INSERT INTO 'likes'('user_id', 'post_id') VALUES($user_id, $post_id);");

$result = mysqli_query($connection, "INSERT INTO `likes` ( `user_id`, `post_id`) VALUES ($user_id, $post_id);");
exit();

}

 // UNLIKE


if(isset($_POST['unliked'])){

    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
 
    
    //1 = fetching the right post
 
 $query = "SELECT * FROM posts WHERE post_id= $post_id";
 $postResult = mysqli_query($connection, $query);
 $post = mysqli_fetch_array($postResult);
 $likes = $post['likes'];
 
 // if(mysqli_num_rows($postResult)>= 1) {
 //     echo $post['post_id'];
 // }
 //2 = update post with post
 
//mysqli_query($connection, "DELETE FROM likes('user_id', 'post_id') VALUES($user_id, $post_id)");
mysqli_query($connection, "DELETE FROM  likes WHERE post_id={$post_id} AND user_id={$user_id}");
//mysqli_query($connection, "DELETE FROM likes WHERE post_id = {$unlikepost_id} AND user_id = {$unlikeuser_id}");

mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id = $post_id");
 
 //3 = create likes for post
 

 exit();
 
 }
 
 

?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->

            <div class="col-md-8">

             <?php 
 
          if(isset($_GET['p_id'])) {
          $the_post_id = $_GET['p_id'];
          
      $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id =$the_post_id ";
      $send_query = mysqli_query($connection,$view_query);
      if(!$send_query) {
          die("query failed");
      }
         

              $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";    
              $select_all_posts_query = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_all_posts_query)){
                  $post_title =  $row['post_title'];
                  $post_author =  $row['post_author'];
                  $post_date =  $row['post_date'];
                  $post_image =  $row['post_image'];
                  $post_content =  $row['post_content'];
                  $post_like_num = $row['likes'];
        
           ?>

                  <h1 class="page-header">
                  Page Heading
                  <small>Secondary Text</small>
              </h1>

              <!-- First Blog Post -->
              <h2>
                  <a href="#"><?php echo $post_title ?></a>
              </h2>
              <p class="lead">
                  by <a href="index.php"><?php echo $post_author ?></a>
              </p>
              <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
              <hr>
              <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
              <hr>
              <p><?php echo $post_content ?></p>
              <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

              <hr>

              <?php
                 if(isset($_SESSION['username']))
                 { ?>
                <h3><a class='like' data-toggle="tooltip" title='want to like post?' href='#'><span class="glyphicon glyphicon-thumbs-up"></span>Like</a></h3>
                <br>
                <?php echo"<h3><i>Likes : $post_like_num </h3></i>";?>
                <h3><a class='unlike' data-toggle="tooltip"  title='want to unlike post' href='#'><span class="glyphicon glyphicon-thumbs-down"></span>Unlike</a></h3>

                 <?php } 
                 
                 else
                 {
                     echo" <h3>Please <a href='\cms\loginpage'>Login </a> to like posts</h3>";
                 }
                 
                 
                 ?>


              <div class ="clearfix"></div>

            <?php
            
        }
        
        
        
           }  else {

            header("Location:index.php");
           }








        
        
        ?>
        
             
                <!-- Blog Comments -->

            <?php 
            
            if(isset($_POST['create_comment'])) {
                    // echo $_POST['comment_author']; //To check for data showing.

                    $the_post_id = $_GET['p_id'];

                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];
                                                          


                 if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                    $query = "INSERT INTO comments (comment_post_id,comment_author, comment_email, comment_content, comment_status, comment_date)";
                    $query .= "VALUES ($the_post_id,'{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now()) ";
    
                   $create_comment_query = mysqli_query($connection, $query);
    
                              if(!$create_comment_query ) {
                              die('QUERY FAILED'. mysqli_error($connection));
                          }
                
                          $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                          $query .= "WHERE post_id = $the_post_id ";
                          $update_comment_count = mysqli_query($connection, $query);
    
    
    
     
                } else {
                
                    echo "<script>alert('Fields cannot be empty')</script>";



                }



                }
               
              
            ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action = "" method = "post" role="form">
                        
                    <div class="form-group">
                    <label for="Author">Author</label>
                        <input type="text" name= "comment_author" class ="form-control" name ="comment_author">
                        </div>

                        <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name= "comment_email" class ="form-control" name ="comment_email">
                        </div>

                        <div class="form-group">
                        <label for="comment">Your Comment</label>
                            <textarea name= "comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name = "create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php 
                
                $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                $query .= "AND comment_status = 'approved' ";
                $query .= "ORDER by comment_id DESC ";
                $select_comment_query = mysqli_query($connection, $query);
                if(!$select_comment_query) {
                    die('QUERY FAILED' .mysqli_error($connection));
                }
                while($row = mysqli_fetch_array($select_comment_query)) {
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
                        <h4 class="media-heading"> <?php echo $comment_author; ?>
                            <small><?php echo $comment_date; ?></small>
                        </h4>
                        <?php echo $comment_content; ?>
                    </div>
                </div>
 
                <?php } ?>
            
            </div> 




            <!-- Blog Sidebar Widgets Column -->
        

            <?php include "includes/sidebar.php"; ?>
       
        </div>

        <hr>
    
   <?php
    include "includes/footer.php";
?>


<?php 

function loggedInUserID()
{
    if(isset($_SESSION['username']))

    {
        global $connection;
        $check_username = $_SESSION['username'];
        $query = "SELECT * FROM users WHERE username='{$check_username}'";
        $result = mysqli_query($connection, $query);
        $user_id = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1?$user_id['user_id'] :false;
        
    }
    return false;
}


?>
<?php
       $userid= LoggedInUserID(); 

 ?>

<script>
    $(document).ready(function(){

        $("[data-toggle='tooltip']").tooltip();

        var post_id = <?php echo $the_post_id; ?>;
        var user_id = <?php echo $userid;?>;

        //POST LIKE
        $('.like').click(function(){
            $.ajax({
                url: "post.php?p_id=<?php echo $the_post_id; ?>",
                type: "post",
                data: {
                    'liked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                
                }
            });
        });
        
        //POST UNLIKE

        $('.unlike').click(function(){
            $.ajax({
                url: "post.php?p_id=<?php echo $the_post_id; ?>",
                type: "post",
                data: {
                    'unliked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
            });
        });
    });
</script>



