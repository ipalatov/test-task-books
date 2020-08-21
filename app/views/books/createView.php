<h1>Добавление новой книги</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="title">Название книги</label>
        <input id="title" type="text" name="title" value=""><br>

        <label for="authors_id">Автор</label><br>
        <?php foreach ($authors as $author) : ?>
            <input type="checkbox" id="authors_id" name="authors_id[]" value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?><br>
        <?php endforeach; ?>

        <label for="genre_id">Жанр</label><br>
        <?php foreach ($genres as $genre) : ?>
            <input type="radio" id="genre_id" name="genre_id" value="<?= $genre['id'] ?>"><?= $genre['name'] ?><br>
        <?php endforeach; ?>

        <label for="year">Год издания</label>
        <input id="year" type="number" name="year" value=""><br>

        <p><input type="submit" name="submit" value="Добавить"></p>
    </form>
</div>