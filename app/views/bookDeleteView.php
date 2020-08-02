<h1>Удаление книги</h1>
<br>

<div style="padding: 20px;">
    <span> Удалить книгу "<?= $books['title'] ?>" автора(ов) <?= $books['author'] ?> ? </span>
</div>
<form method="POST" action="">
    <p><input type="submit" name="submit" value="Удалить книгу"></p>
</form>



<div style="padding: 10px;">
    <a href="<?= ROOT . "books/show?id={$books['id']}" ?>">
        Назад </a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . '' ?>books/index">Список книг</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . 'books/create' ?>">Добавить новую книгу</a>
</div>

<div style="padding: 10px;">
    <a href="<?= ROOT . "books/edit?id={$books['id']}" ?>">Редактировать книгу</a>
</div>

<div style="padding: 20px;">
    <a href="<?= ROOT . '' ?>">На главную страницу</a>
</div>