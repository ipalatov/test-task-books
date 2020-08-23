<h1 class="text-center">Изменение автора</h1>
<h2><?= htmlspecialchars($author['name']) ?></h2>

<div>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Фамилия И.О. автора</label>
            <input class="form-control w-50" id="name" type="text" name="name" aria-describedby="nameHelp" value="<?= htmlspecialchars($author['name']) ?>">
            <small id="nameHelp" class="form-text text-muted">символов: мин - 3, макс - 100. Уникальность </small>
        </div>
        <input class="btn btn-primary" type="submit" name="submit" value="Сохранить изменения">
    </form>
</div>

<?php require 'app/views/layouts/authorNav.php' ?>