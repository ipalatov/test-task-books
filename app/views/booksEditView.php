<h1>Редактирование книги</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="title">Название книги</label>
        <input id="title" type="text" name="title" value="<?= $books['title'] ?>"><br>

        <label for="genre_id">Жанр</label><br>
        <?php foreach ($genres as $genre) : ?>
            <input type="radio" <?= ($genre['name'] == $books['genre']) ? 'checked' : '' ?> id="genre_id" name="genre_id" value="<?= $genre['id'] ?>"><?= $genre['name']  ?><br>
        <?php endforeach; ?>

        <?php
        $books['author'] = explode(', ', $books['author']);
        ?>

        <label for="authors_id">Автор</label><br>
        <?php foreach ($authors as $author) : ?>
            <input type="checkbox" <?php foreach ($books['author'] as $bookAuthor) {
                                        if ($bookAuthor == $author['name']) echo 'checked';
                                    }
                                    ?> id="authors_id" name="authors_id[]" value="<?= $author['id'] ?>"><?= $author['name'] ?><br>
        <?php endforeach; ?>

        <label for="year">Год издания</label>
        <input id="year" type="number" name="year" value="<?= $books['year'] ?>"><br>

        <p><input type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/show?id={$books['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . '' ?>books/index">Список книг</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'books/create' ?>">Добавить новую книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/delete?id={$books['id']}" ?>">Удалить книгу</a>
</div>


<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>