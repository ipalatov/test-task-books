<h1>Редактирование книги</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="title">Название книги</label>
        <input id="title" type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>"><br>

        <label for="genre_id">Жанр</label><br>
        <?php foreach ($genres as $genre) : ?>
            <input type="radio" <?= ($genre['name'] == $book['genre']) ? 'checked' : '' ?> id="genre_id" name="genre_id" value="<?= $genre['id'] ?>"><?= $genre['name']  ?><br>
        <?php endforeach; ?>

        <?php
        $book['author'] = explode(', ', $book['author']);
        ?>

        <label for="authors_id">Автор</label><br>
        <?php foreach ($authors as $author) : ?>
            <input type="checkbox" <?php foreach ($book['author'] as $bookAuthor) {
                                        if ($bookAuthor == $author['name']) echo 'checked';
                                    }
                                    ?> id="authors_id" name="authors_id[]" value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?><br>
        <?php endforeach; ?>

        <label for="year">Год издания</label>
        <input id="year" type="number" name="year" value="<?= $book['year'] ?>"><br>

        <p><input type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/show?id={$book['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . '' ?>books/index">Список книг</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'books/create' ?>">Добавить новую книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "books/delete?id={$book['id']}" ?>">Удалить книгу</a>
</div>


<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>