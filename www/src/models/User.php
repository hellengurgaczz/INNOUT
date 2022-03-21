<?php

class User extends Model {
    protected static $tableName = 'users';
    protected static $columns = [
        'id',
        'name',
        'password',
        'email',
        'start_date',
        'end_date',
        'is_admin'
    ];

    public static function getActiveUsersCount() {
        return static::getCount(['raw' => 'end_date IS NULL']);
    }

    public function insert() {
        $this->validate();
        $this->is_admin = $this->is_admin ? 1 : 0;
        if(!$this->end_date) $this->end_date = null;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::insert();
    }

    public function update() {
        $this->validate();
        $this->is_admin = $this->is_admin ? 1 : 0;
        if(!$this->end_date) $this->end_date = null;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::update();
    }


    private function validate() {
        $errors = [];

        if(!$this->name) {
            $errors['name'] = 'Nome é um campo obrigatório.';
        }
    
        if(!$this->email) {
            $errors['email'] = 'E-mail é um nome obrigatório.';
        }elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail não válido.';
        }

        if(!$this->start_date) {
            $errors['start_date'] = 'Data de admissão é um campo obrigatório.';
        } elseif(DateTime::createFromFormat('y-m-d', $this->start_date)) {
            $errors['start_date'] = 'Data de admissão deve seguir o padrão dd/mm/aaaa';
        }

        if($this->end_date && DateTime::createFromFormat('y-m-d', $this->start_date)) {
            $errors['end_date'] = 'Data de admissão deve seguir o padrão dd/mm/aaaa';
        } 

        if(!$this->password) {
            $errors['password'] = 'Senha é um campo obrigatório.';
        }

        if(!$this->confirm_password) {
            $errors['confirm_password'] = 'Senha é um campo obrigatório.';
        }

        if($this->password && $this->confirm_password && $this->password != $this->confirm_password){
            $errors['password'] = 'As senhas não conferem.';
            $errors['confirm_password'] = 'As senhas não conferem.';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}