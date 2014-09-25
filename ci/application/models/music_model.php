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


}
