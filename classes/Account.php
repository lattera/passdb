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
