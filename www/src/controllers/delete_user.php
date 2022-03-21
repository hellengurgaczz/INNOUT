<?php

session_start();
requireValidSession();

$user_delete = User::getOne(['id' => $_GET['delete']]);


function deletar() {
    try{
        $user_delete = User::getOne(['id' => $_GET['delete']]);
        $user_delete->delete($user_delete->id);
        addSuccessMsg('Usuário deletado com sucesso!');
    } catch(Exception $e) {
        if(stripos($e->getMessage(), 'FOREIGN KEY')){
            addErrorMsg('Não é possível deletar usuários com registros de ponto do dia atual!');
        }else {
            addErrorMsg('Algo deu errado! Tente novamente.');
        }
    }
}

loadTemplateView('delete_user', $_GET + ['user_delete' => $user_delete, 'deletar' => 'deletar']);