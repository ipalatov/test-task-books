<h1>Изменение автора</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="name">Фамилия И.О. автора</label>
        <input id="name" type="text" name="name" value="<?= $authors['name'] ?>"><br>

        <p><input type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "authors/show?id={$authors['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . '' ?>authors/index">Список авторов</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . 'authors/create' ?>">Добавить нового автора</a>
</div>

<div style="padding: 10px;">
    <a href="<?= $ini['app_root'] . "authors/delete?id={$authors['id']}" ?>">Удалить автора</a>
</div>

<div style="padding: 20px;">
    <a href="<?= $ini['app_main'] . '' ?>">На главную страницу</a>
</div>