<?php

class gallery_model Extends CI_Model{
	
	function get_galleries($num, $offset)
	{
		$this->db->select('*');
        $this->db->from('galleries');
		$this->db->order_by("date_created", "desc");
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

	function get_gallery($id)
	{
		$this->db->select('*');
        $this->db->from('galleries');
		$this->db->where('id', $id);

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	function get_gallery_images($num, $offset, $id = NULL, $gallery_name = NULL, $current_lightbox_table)
	{

		/*
			Selecting the gallery image with the 'friendly_name' of the gallery
			
			SELECT image_id, filename, galleries.friendly_name 
			FROM (lightbox) LEFT JOIN media ON lightbox.image_id = media.id
			LEFT JOIN galleries ON lightbox.gallery_id = galleries.id
			WHERE galleries.friendly_name = 'header-images'	

		*/

		if($gallery_name != NULL)
		{		
			$this->db->select('image_id, caption, galleries.friendly_name');
			//$this->db->select('gallery_id, media.id, filename, ext, caption, display_name, cropped, filetype');
			$this->db->from($current_lightbox_table);
			$this->db->join('media', $current_lightbox_table.'.image_id = media.id', 'left');
			$this->db->join('galleries', $current_lightbox_table.'.gallery_id = galleries.id', 'left');
			$this->db->where('galleries.friendly_name', $gallery_name);
		}
				
		if($id != NULL)
		{		
			$this->db->select('gallery_id, media.id, filename, ext, caption, display_name, cropped, filetype');
			$this->db->from($current_lightbox_table);
			$this->db->where($current_lightbox_table.'.gallery_id', $id);
			$this->db->join('media', $current_lightbox_table.'.image_id = media.id', 'left');
			$this->db->order_by($current_lightbox_table.'.id', "desc");
			$this->db->limit($num, $offset);
		}

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

	function insert_gallery($data)
	{
		$q = $this->db->insert('galleries', $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}
	
	function update_gallery($data, $id)
	{
		$this->db->where('id', $id);
		$q = $this->db->update('galleries', $data);
		
		if($q)
		{
			return true;
		}
	}
	
	function delete_galleries($data, $current_lightbox_table)
	{

		foreach ($data as $id)
		{
			$this->db->where('id', $id);
			if($query = $this->db->delete('galleries'))
			{
				$this->db->where('gallery_id', $id);
				$this->db->delete($current_lightbox_table);
			}			
		}		
		return true;
	}

	function insert_image($data, $current_lightbox_table)
	{
		$q = $this->db->insert($current_lightbox_table, $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}

	function remove_image($image_id, $gallery_id, $current_lightbox_table)
	{
		$this->db->where('image_id', $image_id);
		$this->db->where('gallery_id', $gallery_id);
		$q = $this->db->delete($current_lightbox_table);
		
		if($q)
		{
			return true;
		}
	}
	
}