<h1 class="text-center">Удаление книги</h1>
<br>

<div style="min-height: 40vh;">
    <h3 class="text-center"> Удалить книгу "<?= htmlspecialchars($book['title']) ?>" автора(ов) <?= htmlspecialchars($book['author']) ?> ? </h3 class="text-center">

    <form method="POST" action="">
        <div class="text-center">
            <input class="btn btn-primary" type="submit" name="submit" value="ДА"> <input class="btn btn-light" type="submit" name="noDelete" value="НЕТ">
        </div>
    </form>
</div>


<?php require 'app/views/layouts/bookNav.php' ?>