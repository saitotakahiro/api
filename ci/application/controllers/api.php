<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * 楽曲
	 */
	public function musics($id = 0)
	{
		// メソッドによって判定する
		if ($_SERVER["REQUEST_METHOD"] != "POST")
		{
			// GETの場合は楽曲情報を取得
			$this->load->model('music_model');
			$music_data = $this->_get_musics($id);
			print json_encode($music_data);
		}
		else
		{
			// POSTの場合は登録
		}

	}

	/**
	 * 楽曲取得
	 */
	private function _get_musics($id)
	{
		// ID指定の場合
		if ($id > 0)
		{
			$music_data = $this->music_model->get_music_by_id($id);
			return $music_data;
		}

		// インプット
		$artist_id = $this->input->get('artist_id');
		$title     = $this->input->get('title');
		if ($this->input->get('limit'))
		{
			$limit = $this->input->get('limit');
		}
		else
		{
			$limit = 100;
		}
		if ($this->input->get('start'))
		{
			$offset = $this->input->get('start') -1;
		}
		else
		{
			$offset = 0;
		}
		return $this->music_model->get_music($artist_id, $title, $limit, $offset);
	}


}
