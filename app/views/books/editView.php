<h1 class="text-center">Редактирование книги </h1>
<h2><?= htmlspecialchars($book['title']) ?></h2>

<?php
$book['author'] = explode(', ', $book['author']);
?>

<div>
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Название книги</label>
            <input class="form-control w-75" id="title" type="text" name="title" aria-describedby="titleHelp" value="<?= htmlspecialchars($book['title']) ?>">
            <small id="titleHelp" class="form-text text-muted">символов: мин - 3, макс - 250. Уникальность </small>
        </div>
        <div class="row">
            <div class="col">
                Автор
                <?php foreach ($authors as $author) : ?>
                    <div class="custom-control custom-checkbox">
                        <input <?php foreach ($book['author'] as $bookAuthor) {
                                    if ($bookAuthor == $author['name']) echo 'checked';
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
                                    ($genre['name'] == $book['genre']) ? 'checked' : ''
                                ?> class="custom-control-input" type="radio" id="genre_id<?= $genre['id']; ?>" name="genre_id" value="<?= $genre['id'] ?>">
                        <label class="custom-control-label" for="genre_id<?= $genre['id']; ?>">
                            <?= $genre['name']  ?>
                        </label>


                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="form-group">
            <label for="year">Год издания</label>
            <input id="year" type="number" name="year" value="<?= $book['year'] ?>">
            <small id="yearHelp" class="form-text text-muted">не больше текущего года</small>
        </div>

        <p><input class="btn btn-primary" type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

<?php require 'app/views/layouts/bookNav.php' ?>