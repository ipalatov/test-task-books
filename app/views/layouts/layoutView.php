<?php if (isset($dataArray)) extract($dataArray);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test task "Books&Authors"</title>
</head>

<body>
    <div>
        <ul>
            <li><a href="<?= $app_root . $_main_ ?>">На главную страницу</a></li>
            <li><a href="<?= $app_root ?>books/index">Список книг</a></li>
            <li><a href="<?= $app_root ?>authors/index">Список авторов</a></li>
            <li><a href="<?= $app_root ?>books/create">Добавить новую книгу</a></li>
            <li><a href="<?= $app_root ?>authors/create">Добавить нового автора</a></li>
        </ul>
    </div>

    <?php if (isset($_SESSION['message'])) : ?>
        <div>
            <?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['error'])) : ?>
        <div>
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif ?>

    <?php include 'app/views/' . $contentView; ?>
</body>

</html>