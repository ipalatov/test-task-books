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

<?php require 'app/views/layouts/bookNav.php' ?>