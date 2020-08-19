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
            <li><a href="<?= $ini['app_root'] . $ini['_main_'] ?>">На главную страницу</a></li>
            <li><a href="<?= $ini['app_root'] ?>books/index">Список книг</a></li>
            <li><a href="<?= $ini['app_root'] ?>authors/index">Список авторов</a></li>
            <li><a href="<?= $ini['app_root'] . 'books/create' ?>">Добавить новую книгу</a></li>
            <li><a href="<?= $ini['app_root'] . 'authors/create' ?>">Добавить нового автора</a></li>
        </ul>
    </div>


    <?php include 'app/views/' . $contentView; ?>
</body>

</html>