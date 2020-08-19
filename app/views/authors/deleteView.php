<h1>Удаление автора</h1>
<br>

<div style="padding: 20px;">
    <span> Удалить автора "<?= htmlspecialchars($author['name']) ?>" ? </span>
</div>
<form method="POST" action="">
    <p><input type="submit" name="submit" value="Удалить автора"></p>
</form>

<?php require 'app/views/layouts/authorNav.php' ?>