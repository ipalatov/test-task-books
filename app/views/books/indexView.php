<h1>Список книг</h1>

<div style="display: flex; flex-flow: row nowrap;">
    <div style="flex-grow: 3;">
        <div style="padding: 20px;">
            <span>Сортировка по:</span>

            <a href="?sort=title">названию |</a>

            <a href="?sort=year"> по году издания</a>
        </div>

        <div style="padding: 20px;">
            <table>
                <tr>
                    <td>
                        <span>Название</span>
                    </td>
                    <td>
                        <span>Жанр</span>
                    </td>
                    <td>
                        <span>Автор</span>
                    </td>
                    <td>
                        <span>Год издания</span>
                    </td>
                </tr>

                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td>
                            <a href="<?= $ini['app_root'] . "books/show?id={$book['id']}" ?>"><?= htmlspecialchars($book['title']) ?></a>
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
            </table>

        </div>
    </div>

    <div style="padding: 20px; flex-grow: 3;">
        <span>Фильр по:</span>
        <form method="POST" action="">
            <p>по автору</p>
            <p>
                <?php foreach ($authors as $author) : ?>
                    <input type="checkbox" name="author[]" value="<?= $author['id'] ?>" <?php if (isset($_SESSION['author'])) {
                                                                                            foreach ($_SESSION['author'] as $sessAuthor) {
                                                                                                if ($sessAuthor == $author['id']) echo 'checked';
                                                                                            }
                                                                                        }
                                                                                        ?>><?= htmlspecialchars($author['name']) ?><Br>
                <?php endforeach; ?>
            </p>
            <p>по жанру</p>
            <p>
                <?php foreach ($genres as $genre) : ?>
                    <input type="checkbox" name="genre[]" value="<?= $genre['id'] ?>" <?php if (isset($_SESSION['genre'])) {
                                                                                            foreach ($_SESSION['genre'] as $sessGenre) {
                                                                                                if ($sessGenre == $genre['id']) echo 'checked';
                                                                                            }
                                                                                        }
                                                                                        ?>><?= $genre['name'] ?><Br>
                <?php endforeach; ?>
            </p>
            <p>по году</p>
            <p>
                <span>c</span><input type="number" name="startYear" value="<?= isset($_SESSION['startYear']) ?  $_SESSION['startYear'] : 0; ?>"><Br>
                <span>по</span><input type="number" name="endYear" value="<?= isset($_SESSION['endYear']) ?  $_SESSION['endYear'] : 0; ?>"><Br>

            </p>
            <p><input type="submit" value="Применить"> <input type="submit" name="reset_filter" value="Сбросить фильтры"></p>


            </p>
        </form>

    </div>


</div>


<div style="padding: 10px;">
    <?php if ($pagination->countPages > 1) echo $pagination->getHTML() ?>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'books/create' ?>">Добавить новую книгу</a>
</div>


<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] ?>">На главную страницу</a>
</div>