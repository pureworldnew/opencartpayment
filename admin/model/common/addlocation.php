<?php
class ModelCommonAddlocation extends Model 
{
	public function addloc($loc_name){

	$query = $this->db->query("INSERT INTO " . DB_PREFIX . "locations SET `location_name` = '" . $loc_name . "'");
	if($query){
		return true;
	   }
	}
	public function getloc(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "locations");
		return $query->rows;
	}
	public function delloc($id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "locations WHERE location_id = '" . (int)$id . "'");
		return true;
	}
	public function get_edit_amount($id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "locations WHERE location_id='$id'");
		return $query->row;
	}
	public function updateloc($data){
		$loc_name = $data['loc_name'];
		$loc_id = $data['loc_id'];
		$this->db->query("UPDATE " . DB_PREFIX . "locations SET location_name = '$loc_name' WHERE location_id = $loc_id");
		return true;
	}

}