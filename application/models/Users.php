<?php

class Users {

    public function getAll() {

        $db = new DbTable_Users();
        $select = $db->select();
        $select->order("id DESC");

        $data = $db->fetchAll($select);
        return $data;
    }

}

?>