<h1 class="text-center">Добавление новой книги</h1>

<div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Название книги</label>
            <input class="form-control w-75" id="title" type="text" name="title" aria-describedby="titleHelp" value="<?= $_SESSION['title'] ?? null ?>">
            <small id="titleHelp" class="form-text text-muted">символов: мин - 3, макс - 250. Уникальность </small>

        </div>
        <div class="row">
            <div class="col">
                Автор
                <?php foreach ($authors as $author) : ?>
                    <div class="custom-control custom-checkbox">
                        <input <?php
                                if (isset($_SESSION['authors_id'])) {
                                    foreach ($_SESSION['authors_id'] as $sessionAuthor) {
                                        if ($sessionAuthor == $author['id']) echo 'checked';
                                    }
                                }
                                ?> class="custom-control-input" type="checkbox" id="authors_id<?= $author['id']; ?>" name="authors_id[]" value="<?= $author['id'] ?>">
                        <label class="custom-control-label" for="authors_id<?= $author['id']; ?>">
                            <?= htmlspecialchars($author['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col">
                Жанр
                <?php foreach ($genres as $genre) : ?>
                    <div class="custom-control custom-radio">
                        <input <?=
                                    (isset($_SESSION['genre_id']) && $genre['id'] == $_SESSION['genre_id']) ? 'checked' : ''
                                ?> class="custom-control-input" type="radio" id="genre_id<?= $genre['id']; ?>" name="genre_id" value="<?= $genre['id'] ?>">
                        <label class="custom-control-label" for="genre_id<?= $genre['id']; ?>">
                            <?= $genre['name'] ?>
                        </label><br>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="year">Год издания</label>
            <input class="form-control w-25" id="year" type="number" name="year" aria-describedby="yearHelp" value="<?= $_SESSION['year'] ?? null ?>">
            <small id="yearHelp" class="form-text text-muted">не больше текущего года</small>
        </div>

        <input class="btn btn-primary" type="submit" name="submit" value="Добавить"><input class="btn btn-light" type="submit" name="reset" value="Сбросить">
    </form>
</div>