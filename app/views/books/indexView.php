<h1 class="text-center">Список книг</h1>

<div class="row">
    <div class="col-9">
        <div>
            Сортировка по:
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item " style="border: 0;"><a href=" ?sort=title">названию</a></li>
                <li class="list-group-item" style="border: 0;">|</li>
                <li class="list-group-item" style="border: 0;"><a href="?sort=year">по году издания</a></li>
                <li class="list-group-item" style="border: 0;">|</li>
                <li class="list-group-item" style="border: 0;"><a href="?">без сортировки</a></li>
                <li class="list-group-item" style="border: 0;">|</li>
                <li class="list-group-item" style="border: 0;"><a href="?sort=id_desc">сначала новые</a></li>
            </ul>

        </div>
        <div style="min-height: 70vh;">
            <table class="table mw-100">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="align-middle">
                            #
                        </th>
                        <th scope="col" class="align-middle">
                            Название
                        </th>
                        <th scope="col" class="align-middle">
                            Жанр
                        </th>
                        <th scope="col" class="align-middle">
                            Автор
                        </th>
                        <th scope="col" class="align-middle">
                            Год издания
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $key => $book) : ?>
                        <tr>
                            <th scope="row" style="width:50px"><?= ($pagination->currentPage - 1) * $pagination->pageSize + $key + 1  ?></th>
                            <td>
                                <a href="<?= $app_root . "books/show?id={$book['id']}" ?>"><?= htmlspecialchars($book['title']) ?></a>
                            </td>
                            <td style="width:140px">
                                <?= $book['genre'] ?>
                            </td>
                            <td style="width:136px">
                                <?= htmlspecialchars($book['author']) ?>
                            </td>
                            <td style="width:130px">
                                <?= $book['year'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <div>
            <?php if ($pagination->countPages > 1) echo $pagination->getHTML() ?>
        </div>
    </div>

    <div class="col">
        <strong>Фильр по:</strong>
        <form method="POST" action="">
            <div class="row">
                <div class="col">
                    по автору
                    <?php foreach ($authors as $author) : ?>
                        <div class="custom-control custom-checkbox">
                            <input <?php if (isset($_SESSION['authorFilter'])) {
                                        foreach ($_SESSION['authorFilter'] as $sessAuthor) {
                                            if ($sessAuthor == $author['id']) echo 'checked';
                                        }
                                    }
                                    ?> class="custom-control-input" id="authors_id<?= $author['id']; ?>" type="checkbox" name="authorFilter[]" value="<?= $author['id'] ?>">
                            <label class="custom-control-label" for="authors_id<?= $author['id']; ?>">
                                <?= htmlspecialchars($author['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col">
                    по жанру
                    <?php foreach ($genres as $genre) : ?>
                        <div class="custom-control custom-checkbox">
                            <input <?php if (isset($_SESSION['genreFilter'])) {
                                        foreach ($_SESSION['genreFilter'] as $sessGenre) {
                                            if ($sessGenre == $genre['id']) echo 'checked';
                                        }
                                    }
                                    ?> class="custom-control-input" id="genre_id<?= $genre['id']; ?>" type="checkbox" name="genreFilter[]" value="<?= $genre['id'] ?>">

                            <label class="custom-control-label" for="genre_id<?= $genre['id']; ?>">
                                <?= $genre['name'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    по году
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="startYearFilter">c</label>
                    <input class="form-control" style="width: 150px;" type="number" id="startYearFilter" name="startYearFilter" value="<?= isset($_SESSION['startYearFilter']) ?  $_SESSION['startYearFilter'] : 0; ?>">
                </div>
                <div class="form-group col">
                    <label for="endYearFilter">по</label>
                    <input class="form-control" style="width: 150px;" type="number" id="endYearFilter" name="endYearFilter" value="<?= isset($_SESSION['endYearFilter']) ?  $_SESSION['endYearFilter'] : 0; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input class="btn btn-primary" type="submit" value="Применить"> <input class="btn btn-light" type="submit" name="reset_filter" value="Сбросить фильтры"></>
                </div>
            </div>
        </form>
    </div>
</div>