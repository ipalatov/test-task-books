<h1>Добавление нового автора</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="name">Фамилия И.О. автора</label>
        <input id="name" type="text" name="name" value=""><br>

        <p><input type="submit" name="submit" value="Добавить"></p>
    </form>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . '' ?>authors/index">Список авторов</a>
</div>

<div style="padding: 20px;">
    <a href="<?= ROOT . '' ?>">На главную страницу</a>
</div>