<h1 class="text-center">Удаление автора</h1>

<div style="min-height: 40vh;">
    <h3 class="text-center"> Удалить автора "<?= htmlspecialchars($author['name']) ?>" ? </h3 class="text-center">

    <form method="POST" action="">
        <div class="text-center">
            <input class="btn btn-primary" type="submit" name="submit" value="ДА"> <input class="btn btn-light" type="submit" name="noDelete" value="НЕТ">
        </div>
    </form>
</div>


<?php require 'app/views/layouts/authorNav.php' ?>