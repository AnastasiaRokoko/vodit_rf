<?php
session_start();

// Подключение к базе данных
require_once "db.php";

// Проверка авторизации пользователя
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Получение данных пользователя из сессии
$user_id = $_SESSION["user_id"];
$fio = $_SESSION["fio"];

// Сообщение для пользователя
$message = "";

// ======================
// Добавление отзыва
// ======================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Получение данных формы отзыва
    $order_id = $_POST["order_id"];
    $text = trim($_POST["text"]);

    // Проверка пустого текста отзыва
    if ($text == "") {

        $message = "Введите текст отзыва.";

    } else {

        // Проверка, оставлял ли пользователь отзыв по этой заявке
        $check = mysqli_query(
            $conn,
            "SELECT id FROM reviews WHERE order_id='$order_id'"
        );

        if (mysqli_num_rows($check) > 0) {

            $message = "Вы уже оставили отзыв по этой заявке.";

        } else {

            // Добавление отзыва в базу данных
            $sql = "INSERT INTO reviews (user_id, order_id, text)
                    VALUES ('$user_id', '$order_id', '$text')";

            if (mysqli_query($conn, $sql)) {

                $message = "Отзыв успешно добавлен.";

            } else {

                $message = "Ошибка добавления отзыва: " . mysqli_error($conn);
            }
        }
    }
}

// ======================
// Получение заявок пользователя
// ======================

$sql = "SELECT
            orders.id,
            orders.course_date,
            orders.payment_method,
            orders.status,
            transports.name,
            transports.type,
            transports.capacity,
            transports.price,
            reviews.text AS review_text
        FROM orders
        INNER JOIN transports ON orders.transport_id = transports.id
        LEFT JOIN reviews ON reviews.order_id = orders.id
        WHERE orders.user_id = '$user_id'
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Ошибка SQL: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>

    <!-- Кодировка страницы -->
    <meta charset="UTF-8">

    <!-- Адаптация под мобильные устройства -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Заголовок вкладки браузера -->
    <title>Личный кабинет</title>

    <!-- Подключение Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Подключение собственных стилей -->
    <link rel="stylesheet" href="css/style.css">

</head>

<!-- bg-light - светлый фон Bootstrap -->
<body class="bg-light">

<!-- container - основной контейнер Bootstrap -->
<div class="container py-5">

    <!-- Верхний блок с логотипом -->
    <div class="text-center mb-4">
        <img
            src="assets/logo.png"
            alt="Логотип"
            class="logo">
    </div>

    <!-- Блок приветствия и кнопок -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h1 class="h3 mb-1">
                Личный кабинет
            </h1>

            <p class="mb-0">
                Здравствуйте, <?= $fio ?>!
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <!-- Кнопка перехода к созданию заявки -->
            <a href="create_order.php" class="btn btn-primary">
                Создать заявку
            </a>

            <!-- Кнопка выхода из аккаунта -->
            <a href="logout.php" class="btn btn-danger">
                Выйти
            </a>

        </div>

    </div>

    <!-- Вывод сообщения пользователю -->
    <?php if ($message != ""): ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php endif; ?>
<!-- Слайдер Bootstrap: 4 изображения, автопереключение каждые 3 секунды -->
<div id="voditSlider"
     class="carousel slide mb-4"
     data-bs-ride="carousel"
     data-bs-interval="3000">

    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="assets/slide1.jpg"
                 class="d-block w-100 slider-img"
                 alt="Катер">
        </div>

        <div class="carousel-item">
            <img src="assets/slide2.jpg"
                 class="d-block w-100 slider-img"
                 alt="Круизный лайнер">
        </div>

        <div class="carousel-item">
            <img src="assets/slide3.jpg"
                 class="d-block w-100 slider-img"
                 alt="Яхта">
        </div>

    </div>

    <!-- Кнопка назад -->
    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#voditSlider"
            data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <!-- Кнопка вперед -->
    <button class="carousel-control-next"
            type="button"
            data-bs-target="#voditSlider"
            data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>
    <!-- Карточка с историей заявок -->
    <div class="card shadow-sm">

        <div class="card-body p-4">

            <h2 class="h4 mb-3">
                История заявок
            </h2>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <!-- table-responsive - адаптивная таблица с прокруткой на маленьких экранах -->
                <div class="table-responsive">

                    <!--
                        table - таблица Bootstrap
                        table-bordered - рамки таблицы
                        table-striped - чередование строк
                        align-middle - выравнивание по вертикали
                    -->
                    <table class="table table-bordered table-striped align-middle">

                        <tr>
                            <th>№</th>
                            <th>Транспорт</th>
                            <th>Тип</th>
                            <th>Вместимость</th>
                            <th>Дата начала курса</th>
                            <th>Оплата</th>
                            <th>Статус</th>
                            <th>Отзыв</th>
                        </tr>

                        <?php while ($order = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?= $order["id"] ?></td>
                                <td><?= $order["name"] ?></td>
                                <td><?= $order["type"] ?></td>
                                <td><?= $order["capacity"] ?> чел.</td>
                                <td><?= $order["course_date"] ?></td>
                                <td><?= $order["payment_method"] ?></td>
                                <td><?= $order["status"] ?></td>

                                <td>
                                    <?php if ($order["review_text"] != ""): ?>

                                        <?= $order["review_text"] ?>

                                    <?php elseif ($order["status"] == "Обучение завершено"): ?>

                                        <!-- Форма добавления отзыва -->
                                        <form method="POST">

                                            <input
                                                type="hidden"
                                                name="order_id"
                                                value="<?= $order["id"] ?>">

                                            <textarea
                                                name="text"
                                                class="form-control mb-2"
                                                placeholder="Оставьте отзыв"></textarea>

                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm">

                                                Отправить отзыв

                                            </button>

                                        </form>

                                    <?php else: ?>

                                        <span class="small-text">
                                            Отзыв можно оставить после завершения курса.
                                        </span>

                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </table>

                </div>

            <?php else: ?>

                <p class="mb-0">
                    У вас пока нет заявок.
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>

<!-- Подключение JavaScript Bootstrap -->
<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>