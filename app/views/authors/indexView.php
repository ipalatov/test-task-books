<h1 class="text-center">Список авторов</h1>
<div>
    <table class="table w-50 mx-auto">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="align-middle">
                    <span>#</span>
                </th>
                <th scope="col" class="align-middle">
                    <span>Автор</span>
                </th>
                <th scope="col" class="align-middle">
                    <span>Количество книг</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $key => $author) : ?>
                <tr>
                    <th scope="row" style="width:50px"><?= $key + 1 ?></th>
                    <td>
                        <a href="<?= $app_root . "authors/show?id={$author['id']}" ?>"><?= htmlspecialchars($author['name']) ?></a>
                    </td>
                    <td  style="width:160px">
                        <span><?= $author['bookNum'] ?> </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>