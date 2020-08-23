<h1 class="text-center">Информация о авторе </h1>

<div>
    <h3><?= htmlspecialchars($author['name']) ?> </h3>
</div>
<div>
    Все книги автора:


    <table class="table w-100">
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
                    Год издания
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $key => $book) : ?>
                <tr>
                    <th scope="row" style="width:50px"><?= $key + 1  ?></th>
                    <td>
                        <a href="<?= $app_root . "books/show?id={$book['id']}" ?>"><?= htmlspecialchars($book['title']) ?></a>
                    </td>
                    <td style="width:140px">
                        <?= $book['genre'] ?>
                    </td>
                    <td style="width:130px">
                        <?= $book['year'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>


    </table>

</div>

<?php require 'app/views/layouts/authorNav.php' ?>