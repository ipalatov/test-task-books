<h1>Информация о авторе </h1>

<div style="padding: 20px;">
    <span> <b><?= htmlspecialchars($author['name']) ?> </b></span>
</div>
<div style="padding: 20px;">
    <span> Все книги автора: </span>


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
                    <span>Год издания</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $key => $book) : ?>
                <tr>
                    <td><?= $key + 1  ?></td>
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
        </tbody>


    </table>

</div>

<?php require 'app/views/layouts/authorNav.php' ?>