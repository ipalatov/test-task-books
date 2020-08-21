<h1>Список книг</h1>

<div style="display: flex; flex-flow: row nowrap;">
    <div style="flex-grow: 3;">
        <div style="padding: 20px;">
            <span>Сортировка по:</span>
            <ul>
                <li><a href="?sort=title">названию | </a></li>
                <li><a href="?sort=year">по году издания | </a></li>
                <li><a href="?">без сортировки</a></li>
            </ul>

        </div>
        <div style="padding: 20px;">
            <table>
                <thead>
                    <tr>
                        <th>
                            <span>#</span>
                        </th>
                        <th>
                            <span>Название</span>
                        </th>
                        <th>
                            <span>Жанр</span>
                        </th>
                        <th>
                            <span>Автор</span>
                        </th>
                        <th>
                            <span>Год издания</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $key => $book) : ?>
                        <tr>
                            <td><?= ($pagination->currentPage - 1) * 10 + $key + 1  ?></td>
                            <td>
                                <a href="<?= $app_root . "books/show?id={$book['id']}" ?>"><?= htmlspecialchars($book['title']) ?></a>
                            </td>
                            <td>
                                <span><?= $book['genre'] ?></span>
                            </td>
                            <td>
                                <span><?= htmlspecialchars($book['author']) ?></span>
                            </td>
                            <td>
                                <span><?= $book['year'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <div style="padding: 20px; flex-grow: 3;">
        <span>Фильр по:</span>
        <form method="POST" action="">
            <p>по автору</p>
            <p>
                <?php foreach ($authors as $author) : ?>
                    <input type="checkbox" name="authorFilter[]" value="<?= $author['id'] ?>" <?php if (isset($_SESSION['authorFilter'])) {
                                                                                                    foreach ($_SESSION['authorFilter'] as $sessAuthor) {
                                                                                                        if ($sessAuthor == $author['id']) echo 'checked';
                                                                                                    }
                                                                                                }
                                                                                                ?>><?= htmlspecialchars($author['name']) ?><Br>
                <?php endforeach; ?>
            </p>
            <p>по жанру</p>
            <p>
                <?php foreach ($genres as $genre) : ?>
                    <input type="checkbox" name="genreFilter[]" value="<?= $genre['id'] ?>" <?php if (isset($_SESSION['genreFilter'])) {
                                                                                                foreach ($_SESSION['genreFilter'] as $sessGenre) {
                                                                                                    if ($sessGenre == $genre['id']) echo 'checked';
                                                                                                }
                                                                                            }
                                                                                            ?>><?= $genre['name'] ?><Br>
                <?php endforeach; ?>
            </p>
            <p>по году</p>
            <p>
                <span>c</span><input type="number" name="startYearFilter" value="<?= isset($_SESSION['startYearFilter']) ?  $_SESSION['startYearFilter'] : 0; ?>"><Br>
                <span>по</span><input type="number" name="endYearFilter" value="<?= isset($_SESSION['endYearFilter']) ?  $_SESSION['endYearFilter'] : 0; ?>"><Br>

            </p>
            <p><input type="submit" value="Применить"> <input type="submit" name="reset_filter" value="Сбросить фильтры"></p>


            </p>
        </form>

    </div>


</div>


<div style="padding: 10px;">
    <?php if ($pagination->countPages > 1) echo $pagination->getHTML() ?>
</div>