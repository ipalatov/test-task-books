<h1>Удаление автора</h1>
<br>

<div style="padding: 20px;">
    <span> Удалить автора "<?= htmlspecialchars($author['name']) ?>" ? </span>
</div>
<form method="POST" action="">
    <p><input type="submit" name="submit" value="Удалить автора"></p>
</form>



<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "authors/show?id={$author['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . '' ?>authors/index">Список авторов</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'authors/create' ?>">Добавить нового автора</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "authors/edit?id={$author['id']}" ?>">Редактировать автора</a>
</div>

<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>