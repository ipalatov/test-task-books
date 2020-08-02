<h1>Информация о авторе </h1>
<br>

<div style="padding: 20px;">
    <span> <b><?= $authors['name'] ?> </b></span>
</div>
<div style="padding: 20px;">
    <span> Все книги автора: </span>


    <table>
        <tr>
            <td>
                <span>Название</span>
            </td>
            <td>
                <span>Жанр</span>
            </td>

            <td>
                <span>Год издания</span>
            </td>
        </tr>

        <?php foreach ($books as $book) : ?>
            <tr>
                <td>
                    <a href="<?= ROOT . "books/show?id={$book['id']}" ?>"><?= $book['title'] ?></a>
                </td>
                <td>
                    <span><?= $book['genre'] ?></span>
                </td>
                <td>
                    <span><?= $book['year'] ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>



<div style="padding: 10px;">
    <a href="<?= ROOT . '' ?>authors/index">Список авторов</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . 'authors/create' ?>">Добавить нового автора</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . "authors/edit?id={$authors['id']}" ?>">Редактировать автора</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . "authors/delete?id={$authors['id']}" ?>">Удалить автора</a>
</div>

<div style="padding: 20px;">
    <a href="<?= ROOT . '' ?>">На главную страницу</a>
</div>