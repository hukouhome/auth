<?php
include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

if(isset($_POST['loginBtn'])){
    //array to hold errors
    $form_errors = array();

//validate
    $required_fields = array('username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    if(empty($form_errors)){

        //collect form data
        $user = $_POST['username'];
        $password = $_POST['password'];

        //check if user exist in the database
        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));

       while($row = $statement->fetch()){
           $id = $row['id'];
           $hashed_password = $row['password'];
           $username = $row['username'];

           if(password_verify($password, $hashed_password)){
               $_SESSION['id'] = $id;
               $_SESSION['username'] = $username;
               header("location: index.php");
           }else{
               $result = "<p style='padding: 20px; color: red; border: 1px solid gray;'> Invalid username or password</p>";
           }
       }

    }else{
        if(count($form_errors) == 1){
            $result = "<p style='color: red;'>There was one error in the form </p>";
        }else{
            $result = "<p style='color: red;'>There were " .count($form_errors). " error in the form </p>";
        }
    }
}
?>

<?php
$page_title = "User Authentication System - Login Page";
include_once 'partials/headers.php';
?>
<div class="container">
    <section class="col col-lg-7">
        <h2>Login Form </h2><hr>

        <?php if(isset($result)) echo $result; ?>
        <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="usernameField">Username</label>
                <input type="text" class="form-control" name="username" id="usernameField" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="passwordField">Password</label>
                <input type="password" class="form-control" name="password" id="passwordField" placeholder="Password">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"> 记住密码
                </label>
            </div>
            <a href="forgot_password.php" >Forgot Password?</a>
            <button type="submit" class="btn btn-primary pull-right" name="loginBtn">登录</button>
        </form>

    </section>
    <p><a href="index.php">Back</a> </p>
</div>

<?php include_once 'partials/footers.php'; ?>
</body>
</html>