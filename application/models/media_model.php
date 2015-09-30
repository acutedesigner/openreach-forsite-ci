<?php

class media_model Extends CI_Model{
	
	function get_files($num, $offset, $file_type)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('filetype', $file_type);
		$this->db->limit($num, $offset);

		$q = $this->db->get();
		
		if($q->num_rows() > 0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}		
	}

	function delete_file($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('media');
	
		return true;		
	}

	function delete_files($data)
	{
		// Get the array and loop through the results
		if($data != NULL)
		{
			foreach($data as $item)
			{
				// Delete from the database
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}


	function delete_image($id)
	{
		// First check if image is in the lightbox
		$this->db->select('*');
		$this->db->from('lightbox');
		$this->db->where('image_id', $id);

		$q = $this->db->get();	
		
		// If so remove from lightbox
		if($q->num_rows() > 0)
		{
			foreach($q->result() as $row)
			{
				$this->db->where('id', $row->id);
				$this->db->delete('lightbox');
			}
			// Delete from media
			$this->db->where('id', $id);
			$this->db->delete('media');			
			return true;
		}
		else
		{
			// Else just delete from the media
			$this->db->where('id', $id);
			$this->db->delete('media');
			return true;
		}		
	}

	function get_file($file_id)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('id', $file_id);

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	function cropped_file($id)
	{
		$data = array(
               'cropped' => '1'
            );
	
		$this->db->where('id', $id);
		$q = $this->db->update('media', $data);
		
		if($q)
		{
			return true;
		}		
	}

	function get_cropped_images($num, $offset, $file_type)
	{
		$this->db->select('*');
		$this->db->from('media');
		$this->db->where('cropped', '1');
		$this->db->limit($num, $offset);

		$q = $this->db->get();
				
		if($q->num_rows() > 0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}		
	}

	function update_file($id, $data)
	{	
		$this->db->where('id', $id);
		$q = $this->db->update('media', $data);
		
		if($q)
		{
			return true;
		}		
	}
}

