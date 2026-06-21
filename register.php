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
    $fio=trim($_POST["fio"]);
    $birthday_date=($_POST["birthday_date"]);
    $phone=trim($_POST["phone"]);
    $email=trim($_POST["email"]);
    //проверка заполнения полей
    if($login==""||$password==""||$fio==""||$birthday_date==""||$phone==""||$email==""){
        $message="Заполните, пожалуйста, все поля.";
    }
    //проверка длины логина
    elseif
        (!preg_match("/^[A-ZA-z0-9]+$/",$login)){
            $message="Логин должен состоять только из латинских букв или цифр";
        }elseif
            (strlen($login)<6){
                $message="Длина логина - не меньше 6 символов.";
            }
        
        //проверка длины пароля
        elseif (strlen($password)<8){
            $message="Длина пароля - не меньше 8 символов.";
        }
        //проверка существования логина
        else{
            $check=mysqli_query($conn,"SELECT id FROM users WHERE login='$login'");
            if (mysqli_num_rows($check)>0){
                $message="Такой логин уже существует.";
            }
            //добавление пользователя в бд
            $sql="INSERT INTO users(login,password,fio,birthday_date,phone,email)
            VALUES('$login','$password','$fio','$birthday_date','$phone','$email')";
            if(mysqli_query($conn,$sql)){
                $message="Регистрация успешно завершена.";
            }else{
                $message="Ошибка регистрации: ".mysqli_error($conn);
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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
                        <h1 class="h3 text-center mb-4">Регистрация</h1>
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
                                <div class="mb-3">
                                    <label class="form-label">ФИО</label>
                                    <input type="text" name="fio" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Дата рождения</label>
                                    <input type="date" name="birthday_date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Телефон</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Почта</label>
                                    <input type="text" name="email" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                            </form>
                            <p class="text-center mt-3 mb-0">
                                Уже есть аккаунт?<a href="login.php">Войти</a>
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