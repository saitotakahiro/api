<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * 楽曲
	 */
	public function musics($id = 0)
	{
		mb_http_output('UTF-8');

		$this->load->model('music_model');
		// メソッドによって判定する
		if ($_SERVER["REQUEST_METHOD"] != "POST")
		{
			// GETの場合は楽曲情報を取得
			$music_data = $this->_get_musics($id);
			if ($music_data)
			{
				print json_encode($music_data);
			}
			else
			{
				// 楽曲が存在しない
                header("HTTP/1.1 404");
			}
			exit;
		}
		else
		{
			// POSTの場合は登録
			$insert_id = $this->_set_musics();
			header("Location: http://54.178.124.55/api/musics/{$insert_id}", true, 201);
			exit;
		}

	}


	/**
	 * 楽曲
	 */
	public function playlists($id = 0)
	{
		mb_http_output('UTF-8');
		$this->load->model('music_model');

			// GETの場合は楽曲情報を取得
			if ( ! $id)
			{
                		header("HTTP/1.1 404");
               			exit;
			}
			$music_data = $this->music_model->get_playlists($id);
			if ($music_data)
			{
				$arr = array(
					'name'    => $id,
					'outline' => $music_data[0]['pout'],
					'musics'  => $music_data,
				);
				print json_encode($arr);
				exit;
			}
			else
			{
	        	        header("HTTP/1.1 404");
		                exit;
			}			

	}

	/**
	 * 楽曲取得
	 */
	private function _get_musics($name)
	{
		// ID指定の場合
		if ( ! is_numeric($name) && $name == 'recent')
		{
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
                	return $this->music_model->get_music_recent($limit, $offset);
		}
		elseif ( ! is_numeric($name) && $name == 'times')
		{
			$id = $this->input->get('id');
			$music_data = $this->music_model->get_music_times($id);
			return $music_data;	
		}
		elseif (is_numeric($name) && $name > 0)
		{
			$music_data = $this->music_model->get_music_by_id($name);
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

	/**
	 * 楽曲登録
	 */
	private function _set_musics()
	{
		// インプット
		if ($this->input->post('artist_id'))
		{
			$artist_id = $this->input->post('artist_id');
		}
		else
		{
			// 必須パラメータ不足
			header("HTTP/1.1 400");
			exit;
		}
		if ($this->input->post('title'))
		{
			$artist_id = $this->input->post('title');
		}
		else
		{
			// 必須パラメータ不足
			header("HTTP/1.1 400");
			exit;
		}
		$outline   = NULL;
		if ($this->input->post('outline'))
		{
			$outline = $this->input->post('outline');
		}
		return $this->music_model->set_music($artist_id, $title, $outline);
	}

}
