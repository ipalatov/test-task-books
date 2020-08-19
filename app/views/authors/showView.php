<h1>Информация о авторе </h1>

<div style="padding: 20px;">
    <span> <b><?= htmlspecialchars($author['name']) ?> </b></span>
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
                    <a href="<?= $ini['app_root'] . "books/show?id={$book['id']}" ?>"><?= htmlspecialchars($book['title']) ?></a>
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

<?php require 'app/views/layouts/authorNav.php' ?>