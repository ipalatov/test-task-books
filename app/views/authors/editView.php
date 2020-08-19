<h1>Изменение автора</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="name">Фамилия И.О. автора</label>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($author['name']) ?>"><br>

        <p><input type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

<?php require 'app/views/layouts/authorNav.php' ?>