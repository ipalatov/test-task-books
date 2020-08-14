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
                <span><?= htmlspecialchars($books['title']) ?></span>
            </td>
            <td>
                <span><?= $books['genre'] ?></span>
            </td>
            <td>
                <span><?= htmlspecialchars($books['author']) ?></span>
            </td>
            <td>
                <span><?= $books['year'] ?></span>
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
    <a href="<?= $ini['app_root'] . "books/edit?id={$books['id']}" ?>">Редактировать книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/delete?id={$books['id']}" ?>">Удалить книгу</a>
</div>

<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>