<?php
session_start();

// Подключение к базе данных
require_once "db.php";

// Сообщение для администратора
$message = "";

// ======================
// Авторизация администратора
// ======================

// Проверка отправки формы входа администратора
if (isset($_POST["admin_login"])) {

    // Получение логина и пароля из формы
    $login = trim($_POST["login"]);
    $password = trim($_POST["password"]);

    // Проверка данных администратора по заданию
    if ($login == "Admin26" && $password == "Demo20") {
        $_SESSION["admin"] = true;
    } else {
        $message = "Неверный логин или пароль администратора.";
    }
}

// Если администратор не авторизован — показываем форму входа
if (!isset($_SESSION["admin"])) {
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход администратора</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-md-6 col-lg-5">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <div class="text-center mb-3">
                        <img src="assets/logo.png"
                             alt="Конференции.РФ"
                             class="logo">
                    </div>

                    <h1 class="h3 text-center mb-4">
                        Вход администратора
                    </h1>

                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Логин</label>
                            <input type="text"
                                   name="login"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password"
                                   name="password"
                                   class="form-control">
                        </div>

                        <button type="submit"
                                name="admin_login"
                                class="btn btn-primary w-100">
                            Войти
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    exit;
}

// ======================
// Изменение статуса заявки
// ======================

if (isset($_POST["change_status"])) {

    // Получение id заявки и нового статуса
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];

    // Обновление статуса заявки
    $sql = "UPDATE orders
            SET status='$status'
            WHERE id='$order_id'";

    if (mysqli_query($conn, $sql)) {
        $message = "Статус заявки успешно изменён.";
    } else {
        $message = "Ошибка изменения статуса: " . mysqli_error($conn);
    }
}

// ======================
// Получение всех заявок
// ======================

$sql = "SELECT
            orders.id,
            orders.course_date,
            orders.payment_method,
            orders.status,
            users.fio,
            users.birthday_date,
            users.phone,
            users.email,
            transports.name,
            transports.type,
            transports.capacity
        FROM orders
        INNER JOIN users ON orders.user_id = users.id
        INNER JOIN transports ON orders.transport_id = transports.id
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Ошибка SQL: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <!-- Адаптация под мобильные устройства -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Панель администратора</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Собственные стили -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="text-center mb-3">
                <img src="assets/logo.png"
                     alt="Транспорт.РФ"
                     class="logo">
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h1 class="h3 mb-1">
                        Панель администратора
                    </h1>

                    <p class="mb-0">
                        Управление заявками пользователей
                    </p>
                </div>

                <a href="logout.php" class="btn btn-danger">
                    Выйти
                </a>

            </div>

        </div>
    </div>

    <?php if ($message != ""): ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <h2 class="h4 mb-3">
                Все заявки
            </h2>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-striped align-middle">

                        <tr>
                            <th>№</th>
                            <th>ФИО</th>
                            <th>Дата рождения</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Транспорт</th>
                            <th>Тип</th>
                            <th>Вместимость</th>
                            <th>Дата начала курса</th>
                            <th>Оплата</th>
                            <th>Статус</th>
                            <th>Изменить статус</th>
                        </tr>

                        <?php while ($order = mysqli_fetch_assoc($result)): ?>

                            <tr>
                                <td><?= $order["id"] ?></td>
                                <td><?= $order["fio"] ?></td>
                                <td><?= $order["birthday_date"] ?></td>
                                <td><?= $order["phone"] ?></td>
                                <td><?= $order["email"] ?></td>
                                <td><?= $order["name"] ?></td>
                                <td><?= $order["type"] ?></td>
                                <td><?= $order["capacity"] ?> чел.</td>
                                <td><?= $order["course_date"] ?></td>
                                <td><?= $order["payment_method"] ?></td>
                                <td><?= $order["status"] ?></td>

                                <td>
                                    <form method="POST" class="d-flex gap-2 flex-wrap">

                                        <input type="hidden"
                                               name="order_id"
                                               value="<?= $order["id"] ?>">

                                        <select name="status"
                                                class="form-select form-select-sm">

                                            <option value="Новая">
                                                Новая
                                            </option>

                                            <option value="Идет обучение">
                                                Идет обучение
                                            </option>

                                            <option value="Обучение завершено">
                                                Обучение завершено
                                            </option>

                                        </select>

                                        <button type="submit"
                                                name="change_status"
                                                class="btn btn-primary btn-sm">
                                            Сохранить
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </table>

                </div>

            <?php else: ?>

                <p class="mb-0">
                    Заявок пока нет.
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>