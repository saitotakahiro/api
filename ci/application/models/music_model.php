<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Music_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
	$this->load->database();
    }

    /**
     * 楽曲情報を取得
     */
    function get_music($artist_id = 0, $title = NULL, $limit = 100, $offset = 0)
    {
    	// アーティスト
		if ($artist_id > 0)
		{
			$this->db->where('artist_id', $artist_id);
		}
		// タイトル
		if ($title)
		{
			$this->db->where('title', $title);
		}
		// リミット、オフセット
		$this->db->limit($limit, $offset);
		$query =$this->db->get('music');
		
		return $query->result_array();        
    }

    /**
     * 個別楽曲を取得
     */
    function get_music_by_id($id)
    {
		$this->db->where('id', $id);
		$query =$this->db->get('music');
		
		return $query->result_array();        
    }

    /**
     * 再生回数
     */
    function get_music_times($id)
    {
    	if ( ! $id)
    	{
    		return;
    	}
    	$sql  = "SELECT m.id, count(h.id) as times
   FROM music as m
   LEFT JOIN play_history as h ON m.id = h.music_id
WHERE m.id = {$id}";
		$query =$this->db->query($sql);
		
		return $query->result_array();        
    }

    /**
     * 楽曲情報を登録
     */
    function set_music($artist_id, $title, $outline)
    {
    	// 新規登録
		$this->db->set('artist_id', $artist_id);
		$this->db->set('title', $title);
		$this->db->set('outline', $outline);

		$this->db->insert('music');

		return $this->db->insert_id();
    }

    /**
     * プレイリストを取得
     */
    function get_playlists($id)
    {
    	$sql  = "SELECT m.artist_id, m.id, m.outline, m.title, p.outline as pout
  FROM playlist as p
  JOIN playlist_detail as pd ON p.name = pd.playlist_name
  JOIN music as m ON pd.music_id = m.id
 WHERE p.name = '{$id}'";
	$query =$this->db->query($sql);
		
	return $query->result_array();        
    }

    /**
     * 
     */
    function get_music_recent($limit, $offset)
    {
	$sql = "SELECT music_id as id, created_at as last_played
  FROM play_history
 ORDER BY created_at DESC
 LIMIT {$offset}, {$limit};";
        $query =$this->db->query($sql);

        return $query->result_array();
    }
}
