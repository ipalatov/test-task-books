<h1>Удаление книги</h1>
<br>

<div style="padding: 20px;">
    <span> Удалить книгу "<?= htmlspecialchars($book['title']) ?>" автора(ов) <?= htmlspecialchars($book['author']) ?> ? </span>
</div>
<form method="POST" action="">
    <p><input type="submit" name="submit" value="Удалить книгу"></p>
</form>

<?php require 'app/views/layouts/bookNav.php' ?>