<main class="content">
    <?php
    renderTitle(
        'Deletar Usuário',
        'Essa ação não pode ser desfeita. Confirme o usuário que deseja deletar',
        'icofont-trash'
    );
    ?>

    <div>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <th>Nome</th>
                <th>Email</th>
                <th>Data de admissão</th>
            </thead>
            <tbody>
                <tr>
                    <td><?= $user_delete->name ?></td>
                    <td><?= $user_delete->email ?></td>
                    <td><?= $user_delete->start_date ?></td>
                </tr>

            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-5">
            <h4 class="mr-3">Deletar este usuário?</h4>
            <a href="./users.php" event="<?php deletar() ?>;?>" class="btn btn-danger btn-large ml-3 btn-hover text-white">Confirmar</a>
            <?php

            ?>
        </div>
    </div>
</main>