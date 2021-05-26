<?php

    function imagePlaceHolder($image='')
    {
        if(!$image)
        {
            return 'image_1.jpg';
        }
        else
        {
            return $image;
        }
    }

    function currentUser()
    {
        if(isset($_SESSION['username']))
        {
            return $_SESSION['username'];
        }
        return false;
    }

    function query($query)
    {
        global $connection;
        return mysqli_query($connection, $query);
    }

    function redirect($location)
    {
        header("Location:" . $location);
        exit;

    }

    function ifItIsMethod($method=null)
    {
        if($_SERVER['REQUEST_METHOD'] == strtoupper($method))
        {
            return true;
        }
        return false;
    }

    function isLoggedIn()
    {
        if(isset($_SESSION['user_role']))
        {
            return true;
        }
        return false;
    }
    
    function loggedInUserID()
    {
        if(isLoggedIn())
        {
            $fu_username = $_SESSION['username'];
            $result = query("SELECT * FROM users WHERE username='{$fu_username}'");
            confirmquery($result);
            $user = mysqli_fetch_array($result);
            return mysqli_num_rows($result) >= 1?$user['user_id'] :false;
        }
        return false;
    }

    function userLikedThisPost($post_id)
    {
        $result = query("SELECT * FROM likes WHERE user_id=" . loggedInUserID() . " AND post_id={$post_id}");
        return mysqli_num_rows($result) >=1 ?true : false;
    }

    function getPostLikes($post_id)
    {
        $result = query("SELECT * FROM likes WHERE post_id={$post_id}");
        confirmquery($result);
        echo mysqli_num_rows($result);
    }

    function checkIfUserIsLoggedInAndRedirect($redirectLocation=null)
    {
        if(isLoggedIn())
        {
            redirect($redirectLocation);
        }
    
    }
    
    function username_exists($username)
    {
        global $connection;
        $query = "SELECT username FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        if(mysqli_num_rows($result) > 0) 
        {
            return true;
        } else 
        {
            return false;
        }
    }
  
    function email_exists($email)
    {
        global $connection;
        $query = "SELECT user_email FROM users WHERE user_email = '$email'";
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        if(mysqli_num_rows($result) > 0) 
        {
            return true;
        } else 
        {
            return false;
        }
    }

    function escape($string)
    {
        global $connection;
        return mysqli_real_escape_string($connection, trim($string));
    }

    function users_online()
    {
        if(isset($_GET['onlineusers']))
        {
            global $connection;
            if(!$connection)
            {
                session_start();
                include('../includes/db.php');
                $session = session_id();
                $time = time();
                $time_out_in_seconds = 20;
                $time_out  = $time - $time_out_in_seconds;
            
                $query = "SELECT * FROM users_online WHERE online_session = '$session'";
                $send_query = mysqli_query($connection, $query);
                $count = mysqli_num_rows($send_query);
                
                if($count == NULL)
                {
                    mysqli_query($connection, "INSERT INTO users_online(online_session, online_time) VALUES('{$session}','{$time}')");
                }
                else
                {
                    mysqli_query($connection, "UPDATE users_online SET online_time = '{$time}' WHERE online_session = '{$session}'");
                }
            
                $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE online_time > '$time_out'");
                $count_user = mysqli_num_rows($users_online_query);
                echo $count_user;
            }
            
        }
    }

    users_online();

    function confirmquery($result)
    {   
        global $connection;
        if(!$result)
        {
            die("Query Failed" . mysqli_error($connection));
        }
        
    }
    
    function insert_categories()
    {
        global $connection;
        if(isset($_POST['submit'])) {
            $cat_title = $_POST['cat_title'];
            if($cat_title == ""|| empty($cat_title)) {
                echo "This field should not be empty";
            }
            else {
                $query = "INSERT INTO categories(cat_title) ";
                $query .= "VALUE('{$cat_title}') ";

                $create_category_query = mysqli_query($connection, $query);

                if(!$create_category_query) {
                    die('QUERY FAILED' . mysqli_error($connection));
                }
                header('Location: includes/result.php');
            }
        }
    }
    
    function findALLCategories()
    {
        global $connection;
            // find all categories
            $query = "SELECT * FROM categories";
            $select_categories = mysqli_query($connection, $query);
        
            while($row = mysqli_fetch_assoc($select_categories))
            {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<tr>";
                echo "<td>{$cat_id}</td>";
                echo "<td>{$cat_title}</td>";
                echo "<td><a href='categories.php?delete=$cat_id'>Delete</a></td>";
                echo "<td><a href='categories.php?edit=$cat_id'>Update</a></td>";
                echo "</tr>";
            }
    }


    function deleteCategories()
    {
        global $connection;
        if(isset($_GET['delete'])) {
            $the_cat_id = $_GET['delete'];

            $query = "DELETE FROM categories WHERE cat_id = $the_cat_id";
            $delete_query = mysqli_query($connection,$query);
            header("Location: categories.php");
        }
    }

    function login_user($username, $password)
    {
        global $connection;
        $username = trim($username);
        $password = trim($password);
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        $query = "SELECT * FROM users WHERE username = '{$username}' ";
        $select_user_query = mysqli_query($connection, $query);
        if (!$select_user_query) 
        {
   
            die("QUERY FAILED" . mysqli_error($connection));
   
        }
   
   
        while ($row = mysqli_fetch_array($select_user_query)) 
        {
   
            $db_user_id = $row['user_id'];
            $db_username = $row['username'];
            $db_user_password = $row['user_password'];
            $db_user_firstname = $row['user_firstname'];
            $db_user_lastname = $row['user_lastname'];
            $db_user_role = $row['user_role'];
            if (password_verify($password,$db_user_password)) 
            {
               $_SESSION['user_id'] = $db_user_id;
               $_SESSION['username'] = $db_username;
               $_SESSION['firstname'] = $db_user_firstname;
               $_SESSION['lastname'] = $db_user_lastname;
               $_SESSION['user_role'] = $db_user_role;
                redirect("admin");
            } 
            else 
            {
                return false;
            }
        }
        return true;
    }
?>