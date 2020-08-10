<h1>Список авторов</h1>
<div>
    <table>
        <tr>
            <td>
                <span>Автор</span>
            </td>
            <td>
                <span>Количество книг</span>
            </td>
        </tr>

        <?php foreach ($authors as $author) : ?>
            <tr>
                <td>
                    <a href="<?= $ini['app_root'] . "authors/show?id={$author['id']}" ?>"><?= $author['name'] ?></a>
                </td>
                <td>
                    <span><?= $author['bookNum'] ?> </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'authors/create' ?>">Добавить нового автора</a>
</div>


<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] ?>">На главную страницу</a>
</div>