<h1>Изменение автора</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="name">Фамилия И.О. автора</label>
        <input id="name" type="text" name="name" value="<?= $authors['name'] ?>"><br>

        <p><input type="submit" name="submit" value="Сохранить изменения"></p>
    </form>
</div>

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
    <a href="<?= ROOT . "authors/delete?id={$authors['id']}" ?>">Удалить автора</a>
</div>

<div style="padding: 20px;">
    <a href="<?= ROOT . '' ?>">На главную страницу</a>
</div>