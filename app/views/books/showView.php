<h1>Информация о книге</h1>
<br>

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

        <tr>
            <td>
                <span><?= htmlspecialchars($book['title']) ?></span>
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
    </table>

</div>


<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . '' ?>books/index">Список книг</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'books/create' ?>">Добавить новую книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/edit?id={$book['id']}" ?>">Редактировать книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/delete?id={$book['id']}" ?>">Удалить книгу</a>
</div>

<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>