<?php
session_start();
//подключение к бд
require_once "db.php";
//проверка авторизации пользователя
if(!isset($_SESSION["user_id"])){
    header("Location:login.php");
    exit;
}
//получение id текущего пользователя из сессии
$user_id=$_SESSION["user_id"];
$message="";
//получение списка видов транспорта из бд
$transports=mysqli_query($conn,"SELECT*FROM transports");
//проверка отправки формы создания заявки
if($_SERVER["REQUEST_METHOD"]=="POST"){
    //получение данных из формы
    $transport_id=$_POST["transport_id"];
    $course_date=$_POST["course_date"];
    $payment_method=$_POST["payment_method"];
    //проверка заполнения полей
    if($transport_id==""||$course_date==""||$payment_method==""){
        $message="Заполните все поля.";
    }else{
        //добавление новой заявки в бд
        $sql="INSERT INTO orders(user_id,transport_id,course_date,payment_method,status)
        VALUES('$user_id','$transport_id','$course_date','$payment_method','Новая')";
        if (mysqli_query($conn,$sql)){
            $message="Заявка успешно создана.";
        }else{
            $message="Ошибка создания заявки: ".mysqli_error($conn);
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
                        <h1 class="h3 text-center mb-4">Создание заявки</h1>
                        <!--ссылка на личный кабинет-->
                        <p class="text-center">
                            <a href="profile.php">Личный кабинет</a>
                        </p>
                        <?php if($message!=""):?>
                            <div class="alert alert-info"><?=$message?></div>
                            <?php endif;?>
                         <!--форма создания заявки-->
                         <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Транспорт</label>
                                <select name="transport_id" class="form-select">
                                    <option value="">--выберите транспорт--</option>
                                    <?php while($transport=mysqli_fetch_assoc($transports)):?>
                                        <option value="<?=$transport["id"]?>">
                                            <?=$transport["name"]?>-
                                            <?=$transport["type"]?>,до
                                            <?=$transport["capacity"]?> чел.
                                        </option>
                                        <?php endwhile;?>
                                </select>
                            </div>
                             <div class="mb-3">
                                    <label class="form-label">Дата начала курса</label>
                                    <input type="date"
                                    name="course_date"
                                    class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Способ оплаты</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">--выберите способ оплаты--</option>
                                        <option value="Наличные">Наличные</option>
                                        <option value="Банковская карта">Банковская карта</option>
                                        <option value="Безналичный расчет">Безналичный расчет</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Создать заявку</button>
                            </form>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <script src="js/bootstrap.bundle.min.js"></script>
                                        </body>
                                        </html>
                         </form>   
