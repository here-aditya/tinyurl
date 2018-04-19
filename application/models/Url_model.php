<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Url_model extends CI_Model 
{
	protected $url_table = 'tiny_urls';
	protected $counter_table = 'url_counters';


	public function fetchShortCode($long_url)
	{
		$result = $this->db->select('unique_code')
				->from($this->url_table)
				->where('main_url', $long_url)
				->get();

		return ($result->num_rows() > 0) ? $result->row()->unique_code : null;
	}

	public function insertLongUrl($long_url, $tstamp)
	{
		$data = array('main_url' => $long_url, 'created_date' => $tstamp);
		$this->db->insert($this->url_table, $data);

		return $this->db->insert_id();
	}
	
	public function updateShortUrl($code, $id)
	{
		$this->db->set('unique_code', $code);
		$this->db->where('id', $id);
		$this->db->update($this->url_table); 

		return $this->db->affected_rows() > 0 ? true : false;
	}

	public function fetchLongUrl($code)
	{
		$result = $this->db->select('id, main_url')
				->from($this->url_table)
				->where('unique_code', $code)
				->get();
		return ($result->num_rows() > 0) ? $result->row_array() : null;
	}

	public function incrementCounter($ref_id, $tstamp)
	{
		$result = $this->db->select('counter')
							->from($this->counter_table)
							->where('url_refid', $ref_id)
							->get();
		$counter = ($result->num_rows() > 0) ? $result->row()->counter : 0;
		
		if($counter == 0) {	
			$insert_data = array('url_refid' => $ref_id, 'counter' => $counter, 'created_date' => $tstamp);
			$this->db->insert($this->counter_table, $insert_data);
			return $this->db->insert_id() > 0 ? true : false;
		} else {
			$this->db->set('counter', $counter + 1);
			$this->db->where('url_refid', $ref_id);
			$this->db->update($this->counter_table);
			return $this->db->affected_rows() > 0 ? true : false;
		}
	}
}