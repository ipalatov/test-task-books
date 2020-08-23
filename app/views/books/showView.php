<h1 class="text-center">Информация о книге</h1>
<br>

<div class="card border-primary mb-3">



    <div class="card-header">Название</div>
    <div class="card-body text-primary">
        <h5 class="card-title text-dark"><?= htmlspecialchars($book['title']) ?></h5>
    </div>
    <div class="card-header">Жанр</div>
    <div class="card-body text-primary">
        <h5 class="card-title text-dark"><?= $book['genre'] ?></h5>
    </div>
    <div class="card-header">Автор</div>
    <div class="card-body text-primary">
        <h5 class="card-title text-dark"><?= htmlspecialchars($book['author']) ?></h5>
    </div>
    <div class="card-header">Год издания</div>
    <div class="card-body text-primary">
        <h5 class="card-title text-dark"><?= $book['year'] ?></h5>
    </div>
    <div class="card-header">Описание</div>
    <div class="card-body text-primary">
        <p class="card-text text-dark">Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati vitae ea at ut laudantium suscipit accusamus magnam, blanditiis quia? Tenetur consequuntur accusamus ad qui totam aliquam sit delectus accusantium adipisci.</p>
    </div>

</div>

<?php require 'app/views/layouts/bookNav.php' ?>