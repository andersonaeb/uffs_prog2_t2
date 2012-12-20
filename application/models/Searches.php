<?php

class Searches {

    public function getTop($limit = 10) {

        $db = new DbTable_Searches();
        $select = $db->select();
        $select->order("count DESC")->limit($limit, 0);

        $data = $db->fetchAll($select);
        return $data;
    }

    public function get($query) {
        $key = md5(str_replace(', ', ',', $query));
        $db = new DbTable_Searches();
        $select = $db->select()->where('query_key = ?', $key);

        return $db->fetchRow($select);
    }

    public function insert($query, $song_name = '', $artist_name = '', $image = '') {
        $key = md5(str_replace(', ', ',', $query));
        $db = new DbTable_Searches();
        $select = $db->select()->where('query_key = ?', $key);

        $res = $db->fetchRow($select);
        if (count($res) > 0) {
            $res->count++;
            $res->save();
        } else {
            $db->insert(array(
                'query' => $query,
                'query_key' => $key,
                'count' => 1,
                'song_name' => $song_name,
                'artist_name' => $artist_name,
                'image' => $image
            ));
        }
    }

}

?>