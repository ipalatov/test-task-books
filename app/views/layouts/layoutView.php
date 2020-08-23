<?php if (isset($dataArray)) extract($dataArray);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test task "Books&Authors"</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div class="container-xl shadow-lg mb-5 bg-white rounded" ">
        <div>
            <ul class=" nav justify-content-center">
        <li class="nav-item"><a class="nav-link btn btn-secondary" href="<?= $app_root . $_main_ ?>">На главную страницу</a></li>
        <li class="nav-item"><a class="nav-link btn btn-secondary" href="<?= $app_root ?>books/index">Список книг</a></li>
        <li class="nav-item"><a class="nav-link btn btn-secondary" href="<?= $app_root ?>authors/index">Список авторов</a></li>
        <li class="nav-item"><a class="nav-link btn btn-secondary" href="<?= $app_root ?>books/create">Добавить новую книгу</a></li>
        <li class="nav-item"><a class="nav-link btn btn-secondary" href="<?= $app_root ?>authors/create">Добавить нового автора</a></li>
        </ul>
    </div>

    <div class="p-3 w-75 mx-auto" >
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
    </div>

    <div style="min-height: 85vh;">
        <?php include 'app/views/' . $contentView; ?>
    </div>

    <div class="text-center"><?= date('Y') ?> - <a href="mailto:ivan.s.palatov@gmail.com">Иван Палатов</a></div>

    </div>

    <!-- Bootstrap JavaScript-->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>