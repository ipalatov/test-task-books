<h1>Добавление нового автора</h1>

<div style="padding: 20px;">
    <form method="POST" action="">
        <label for="name">Фамилия И.О. автора</label>
        <input id="name" type="text" name="name" value="<?= $_SESSION['name'] ?? null ?>"><br>

        <p><input type="submit" name="submit" value="Добавить"></p>
    </form>
</div>