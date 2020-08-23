<div>
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="<?= $app_root . "books/show?id={$book['id']}" ?>">Просмотр книги</a></li>
        <li class="nav-item"><a class="nav-link"  class="nav-link"href="<?= $app_root . "books/edit?id={$book['id']}" ?>">Редактирование книги</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= $app_root . "books/delete?id={$book['id']}" ?>">Удаление книги</a></li>
    </ul>
</div>