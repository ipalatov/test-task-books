<h1>Список авторов</h1>
<div>
    <table>
        <thead>
            <tr>
                <th>
                    <span>#</span>
                </th>
                <th>
                    <span>Автор</span>
                </th>
                <th>
                    <span>Количество книг</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $key => $author) : ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td>
                        <a href="<?= $ini['app_root'] . "authors/show?id={$author['id']}" ?>"><?= htmlspecialchars($author['name']) ?></a>
                    </td>
                    <td>
                        <span><?= $author['bookNum'] ?> </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>