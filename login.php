<?php
session_start();
//подключение к бд
require_once "db.php";
$message="";
//проверка отправки формы 
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //получение данных из формы
    $login=trim($_POST["login"]);
    $password=trim($_POST["password"]);
    
    //проверка заполнения полей
    if($login==""||$password==""){
        $message="Заполните, пожалуйста, все поля.";
    }else{
        //поиск пользователя по логину и паролю
        $sql="SELECT * FROM users WHERE login='$login'AND password='$password'";
        $result=mysqli_query($conn,$sql);
        //если найден ровно один пользователь
        if(mysqli_num_rows($result)==1){
            //получение данных пользователя
            $user=mysqli_fetch_assoc($result);
            //сохранение данных пользователя в сессию
            $_SESSION["user_id"]=$user["id"];
            $_SESSION["fio"]=$user["fio"];
            //переход в личный кабинет
            header("Location: profile.php");
            exit;
        }else{
            $message="Неверный логин или пароль.";
        }
    }
    
        }
    

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
<!--подключение своих стилей+стилей Bootstrap-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <!--логотип сайта-->
                            <img
                            src="assets/logo.png"
                            width="120"
                            alt="Логотип">
                        </div>
                        <h1 class="h3 text-center mb-4">Авторизация</h1>
                        <!--вывод сообщения пользователю-->
                        <?php if($message!=""):?>
                            <div class="alert alert-info"><?=$message?></div>
                            <?php endif;?>
                            <!--форма регистрации-->
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Логин</label>
                                    <input type="text" name="login" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Пароль</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Войти</button>
                            </form>
                            <p class="text-center mt-3 mb-0">
                                Еще не зарегистрированы?<a href="register.php">Зарегистрироваться</a>
                            </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--подключение JS Bootstrap-->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>