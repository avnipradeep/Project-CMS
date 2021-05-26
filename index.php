
<?php include "includes/db.php";?>
<?php include "includes/header.php";?>

    <!-- Navigation -->
   
 <?php 
include "includes/navigation.php";
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->



            <div class="col-md-8">

            <?php


                $limit=4;

                if(isset($_GET['page']))
                {

                $page=$_GET['page'];
                }
                else
                {
                    $page=1;
                }
                $offset=($page-1)*$limit;
            ?>

        <?php


              $query = "SELECT * FROM posts  LIMIT {$offset},{$limit}";;
              $select_all_posts_query = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_all_posts_query)){
                  $post_id =  $row['post_id'];
                  $post_title =  $row['post_title'];
                  $post_author =  $row['post_author'];
                  $post_date =  $row['post_date'];
                  $post_image =  $row['post_image'];
                  $post_content =  substr($row['post_content'],0,100);
                  $post_status =  $row['post_status'];
                
           ?>

                  <!-- <h1 class="page-header">
                  Page Heading
                  <small>Secondary Text</small>
              </h1> -->

              <!-- First Blog Post -->


              <h2>
                  <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
              </h2>
              <p class="lead">
                  by <a href="index.php"><?php echo $post_author ?></a>
              </p>
              <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
              <hr>
              <a href="post.php?p_id=<?php echo $post_id; ?>">
              <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
              </a>
              <hr>
              <p><?php echo $post_content ?></p>
              <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

              <hr>

            <?php }  ?>
        
            

            </div> 

            


            <!-- Blog Sidebar Widgets Column -->
        

            <?php include "includes/sidebar.php"; ?>
       
        </div>

        <hr>
<?php
       $query1="SELECT * FROM posts";
       $result1= mysqli_query($connection,$query1); 
    
       if(mysqli_num_rows($result1)>0)
       {
           $total_records=mysqli_num_rows($result1);
           $limit=4;
           $total_pages= ceil( $total_records/$limit);
    
          echo"<ul class='pager'>";
       
           if($page>1)
           {
          echo"<li class='previous'>";
          $prev=$page-1;
         echo"<a href='/cms/index.php?page={$prev}'>&larr; Older</a>";
           }
    
           for($i=1; $i<=$total_pages;$i++)
           {
               if($i==$page)
               {
                   $active="active";
                   $color='#DC143C';
               }
               else
               {
                   $active="";
                   $color='#0000FF';
                   
               }
         
    
               echo"<li><a style='color:{$color}' href='/cms/index.php?page={$i}'> $i </a></li>";
    
           }
    
           if($page<$total_pages)
           {
          echo" <li class='next'> ";
          $next=$page+1;
         echo"  <a href='/cms/index.php?page={$next}'>Newer &rarr;</a>";
           echo" </ul>";
           }
       }
       
      
       ?>

        
    
   <?php
    include "includes/footer.php";
?>
