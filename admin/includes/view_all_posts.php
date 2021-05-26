
<form action="" method="post">
            <!-- <div id="bulkOptionContainer" class="col-xs-4"> -->

            <div>
                <a class="btn btn-primary" href="/cms/admin/posts.php?source=add_post">Add New</a>

            </div>
            <table class="table table-bordered table-hover ">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Category ID</th>     
                                <th>Status</th>
                                <th>Image</th>
                                <th>Tags</th>
                                <th>View Count</th>
                                <th>Comments count</th>
                                <th>Date</th>
                                <th>View Option</th>
                                <th>Edit Option</th>
                                <th>Delete Option</th>
                               
                

                            </tr>
                        </thead>
                        <tbody>
                            
            <?php
                //$currentusser = currentUser();

                $query = "SELECT * FROM posts ORDER BY post_id DESC";
                $select_posts = mysqli_query($connection, $query);
                                    
                while($row = mysqli_fetch_assoc($select_posts))
                {
                    $post_id = $row['post_id'];
                    $post_author = $row['post_author'];
                    $post_title = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_tags = $row['post_tags'];
                    $post_comment_count = $row['post_comment_count'];
                    $post_date = $row['post_date'];
                    $post_views_count = $row['post_views_count'];
                    
                    echo "<tr>";
             ?>
                     
            <?php
                    echo "<td>{$post_id}</td>";
                    echo "<td>{$post_author}</td>";
                    echo "<td>{$post_title}</td>";

                    $query = "SELECT * FROM categories WHERE cat_id = $post_category_id";
                    $select_categories = mysqli_query($connection, $query);
                
                    while($row = mysqli_fetch_assoc($select_categories))
                    {
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];


                    
                    }

                    echo "<td>{$cat_title}</td>";

                    echo "<td>{$post_status}</td>";
                    echo "<td><img src='../images/$post_image' alt = 'image' width='100'></td>";
                    echo "<td>{$post_tags}</td>";
                    echo "<td><a href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                    echo "<td> $post_comment_count</td>";
                    echo "<td>{$post_date}</td>";
                    
                    echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                    echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
                    echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete'); \" href='posts.php?delete={$post_id}'>Delete</a></td>";
                    //echo "<td><a rel='$post_id' class='delete_link' href='javascript:void(0)' >Delete</a></td>";
                    echo "</tr>";
                                        
                                        
                }


            ?>


                                
        </tbody>
    </table>
</form>
<?php
    if(isset($_GET['delete']))
    {
        $the_post_id = $_GET['delete'];
        $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: posts.php");
    }

    if(isset($_GET['reset']))
    {
        $the_post_id = $_GET['reset'];
        $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = {$the_post_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: posts.php");
    }
?>

<script>
    $(document).ready(function(){
        $(".delete_link").on('click', function(){
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete="+ id +" ";
            $(".modal_delete_link").attr("href", delete_url);
            $("#myModal").modal('show');
            // alert(delete_url);
        });
    });

</script>