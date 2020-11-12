<?php


class ModelModuleRedirects extends Model {

	public function listAllRedirects() {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_redirect rd ORDER by rd.`id`");

		return $query->rows;

	}

	public function deleteAllRedirects(){
	    $this->db->query("DELETE FROM " . DB_PREFIX . "url_redirect");
    }

    public function saveRedirect($data) {

        $query = "INSERT INTO " . DB_PREFIX . "url_redirect (old_url, new_url) VALUES ('" . $this->db->escape($data['old_url']) . "', '" . $this->db->escape($data['new_url']) . "') ON DUPLICATE KEY UPDATE old_url = '". $this->db->escape($data['old_url']) . "', new_url = '" . $this->db->escape($data['new_url']) . "'" ;
        $this->db->query($query);

    }

    public function getTotalCount(){
    	$query = $this->db->query("SELECT COUNT(*) FROM " . DB_PREFIX . "url_redirect");

		return $query->num_rows;
    }

    public function loadData($data){
      
    	
    	 $csvfile="model/module/redirects_404_url.csv";
       
 		   $handle = fopen($csvfile,"r");
        if ( !$handle ) {
          print "Error while importing data.";
          print_r(error_get_last());
          die();
        }
 		   $i = 0;
 		   while(!feof($handle))
    		{
            $returndata = fgetcsv($handle);
            
            
            $broken_url = $returndata[0];
            $fixed_url  = $returndata[1];

            if (is_numeric($fixed_url))
            {
              	$fixed_url = "product_id=".$fixed_url; //if only id is given in column , else do not change anything.
            }


            $query = "INSERT INTO " . DB_PREFIX . "url_redirect (old_url, new_url) VALUES ('" . $this->db->escape($broken_url) . "', '" . $this->db->escape($fixed_url) . "') ON DUPLICATE KEY UPDATE old_url = '". $this->db->escape($broken_url) . "', new_url = '" . $this->db->escape($fixed_url) . "'" ;
           // print $query;


            $this->db->query($query);
            

            //***********************************************************************************************
    		}
        //die();
    }
}