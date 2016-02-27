<?php 
include("config.php"); 

$error=0;

if (isset($_POST['login']) && !empty($_POST['email']) && !empty($_POST['password'])) {

    $email=$mysql->real_escape_string($_POST['email']);
    $password=$mysql->real_escape_string($_POST['password']);

    $login=$mysql->query("SELECT * FROM utilizador WHERE email='$email' AND password='$password'"); 

    if($login->num_rows==1){
        //session_register("userid");
        $row = $login->fetch_assoc();
        $_SESSION['userid']=$row['userid'];
        
        header("location: ./index.php");
        exit;
    }
    else{
        $error=1;
    }
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bloco de Notas</title>
    <meta name="description" content="">
    <meta name="author" content="77896,77907,77969">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="container">

        <div class="row">
        <?php if($error==1){ ?>
        <br>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Email e/ou password errados!
        </div>
        
        <?php } ?>

        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="./login.php">
                            <fieldset>
                                <div class="form-group">
                                    <input name="email" class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input name="password" class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!--<div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="lembrar">Lembrar-me
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" name="login" class="btn btn-lg btn-success btn-block" value="Login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.2.0/metisMenu.min.js"></script>

    <?php $mysql->close(); ?>

</body>
</html>