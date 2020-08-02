<h1>Удаление автора</h1>
<br>

<div style="padding: 20px;">
    <span> Удалить автора "<?= $authors['name'] ?>" ? </span>
</div>
<form method="POST" action="">
    <p><input type="submit" name="submit" value="Удалить автора"></p>
</form>



<div style="padding: 10px;">
    <a href="<?= ROOT . "authors/show?id={$authors['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . '' ?>authors/index">Список авторов</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . 'authors/create' ?>">Добавить нового автора</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . "authors/edit?id={$books['id']}" ?>">Редактировать автора</a>
</div>

<div style="padding: 20px;">
    <a href="<?= ROOT . '' ?>">На главную страницу</a>
</div>