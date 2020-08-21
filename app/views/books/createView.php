<h1>Добавление новой книги</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="title">Название книги</label>
        <input id="title" type="text" name="title" value="<?= $_SESSION['title'] ?? null ?>"><br>

        <label for="authors_id">Автор</label><br>
        <?php foreach ($authors as $author) : ?>
            <input type="checkbox" <?php
                                    if (isset($_SESSION['authors_id'])) {
                                        foreach ($_SESSION['authors_id'] as $sessionAuthor) {
                                            if ($sessionAuthor == $author['id']) echo 'checked';
                                        }
                                    }
                                    ?> id="authors_id" name="authors_id[]" value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?><br>
        <?php endforeach; ?>

        <label for="genre_id">Жанр</label><br>
        <?php foreach ($genres as $genre) : ?>
            <input type="radio" <?= (isset($_SESSION['genre_id']) && $genre['id'] == $_SESSION['genre_id']) ? 'checked' : '' ?> id="genre_id" name="genre_id" value="<?= $genre['id'] ?>"><?= $genre['name'] ?><br>
        <?php endforeach; ?>

        <label for="year">Год издания</label>
        <input id="year" type="number" name="year" value="<?= $_SESSION['year'] ?? null ?>"><br>

        <p><input type="submit" name="submit" value="Добавить"><input type="submit" name="reset" value="Сбросить"></p>
    </form>
</div>