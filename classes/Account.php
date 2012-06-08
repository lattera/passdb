<?php

class Account {
    protected $uid;
    protected $account_id;
    protected $keyid;
    protected $title;
    protected $username;
    protected $password;
    protected $site;
    protected $description;
    protected $notes;
    protected $create_timestamp;
    protected $update_timestamp;
    protected $dirty;

    function __construct() {
        $this->dirty = FALSE;
        $this->account_id = 0;
        $this->notes = array();

        $this->keyid =
            $this->username =
            $this->password =
            $this->site =
            $this->description = "";
    }

    public function __get($name) {
        if ($name == "password") {
            /* TODO - Decrypt first */
            return $this->password;
        }

        return $this->$name;
    }

    public function __set($name, $value) {
        if ($name == "password")
            $this->password = $value;
        else
            $this->$name = $value;
    }

    public function Persist() {
        if ($this->account_id == 0)
            return $this->Create();
        else if ($this->dirty)
            return $this->Update();

        return TRUE;
    }

    public function Delete() {
        foreach ($this->notes as $note)
            $note->Delete();

        db_delete('passdb_accounts')
            ->condition('account_id', $this->account_id)
            ->execute();
    }

    private function Create() {
        $timestamp = time();

        db_insert('passdb_accounts')
            ->fields(array(
                'uid' => $this->uid,
                'create_timestamp' => $timestamp,
                'update_timestamp' => $timestamp,
                'site' => $this->site,
                'username' => $this->username,
                'password' => $this->password,
                'description' => $this->description,
            ))
            ->execute();

        $this->create_timestamp = $this->update_timestamp = $timestamp;
        $this->dirty = FALSE;
        return TRUE;
    }

    private function Update() {
        $this->update_timestamp = time();

        db_update('passdb_accounts')
            ->fields(array(
                'update_timestamp' => $this->update_timestamp,
                'site' => $this->site,
                'username' => $this->username,
                'password' => $this->password,
                'description' => $this->description,
            ))
            ->condition('account_id', $this->account_id)
            ->execute();

        $this->dirty = FALSE;
        return TRUE;
    }

    public static function LoadAll($uid=0) {
        if ($uid == 0)
            return NULL;

        $accounts = array();

        $records = db_select('passdb_accounts', 'a')
            ->fields('a')
            ->execute();

        while (($record = $records->fetchAssoc()))
            $accounts[] = Account::LoadFromRecord($record);

        return $accounts;
    }

    public static function Load($account_id) {
        $record = db_select('passdb_accounts', 'a')
            ->fields('a')
            ->condition('account_id', $account_id, '=')
            ->execute()
            ->fetchAssoc();

        if ($record !== FALSE)
            return Account::LoadFromRecord($record);

        return NULL;
    }

    protected static function LoadFromRecord($record) {
        $account = new Account;
        $account->account_id = $record['account_id'];
        $account->uid = $record['uid'];
        $account->username = $record['username'];
        $account->password = $record['password'];
        $account->site = $record['site'];
        $account->description = $record['description'];
        $account->create_timestamp = $record['create_timestamp'];
        $account->update_timestamp = $record['update_timestamp'];

        return $account;
    }
}

?>
