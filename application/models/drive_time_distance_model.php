<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class drive_time_distance_model extends CI_Model {

	private $salt;

    public function __construct ()
    {
        parent::__construct();
        $this->salt = '$6$rounds=5000$usingashitstringfornidhipasmundra$';
    }

    public function checkDB($src_ref,$dest_ref) {

         $this->db->select('distance,duration')->from('distance_duration')->where(array("src_ref"=> $src_ref , "dest_ref" => $dest_ref))->limit(1);
         $query = $this->db->get();
         return $query->result();
		
    }

    public function searchLocation($place)
    {

        $this->db->select('lat_long_reference')->from('place_reference')->where('place', $place)->limit(1);
        $query = $this->db->get();
        return $query->result();

    }

    public function insertLocation($place,$id)
    {

         $this->db->insert('place_reference', array('place'=> $place , 'lat_long_reference'=> $id));
    }

    public function searchReference($lat_long_addr)
    {

        $latlong_array = explode(',',$lat_long_addr);

        $this->db->select('lat_long_reference')->from('lat_long')->where(array("latitude"=> $latlong_array[0] , "longitude" => $latlong_array[1]))->limit(1);
        $query = $this->db->get();
        return $query->result();

    }

    public function searchLatLong($lat,$long)
    {

        $this->db->select('lat_long_reference')->from('lat_long')->where(array("latitude"=> $lat , "longitude" => $long))->limit(1);
        $query = $this->db->get();
        return $query->result();

    }

    public function insertLatLong($lat,$long)
    {
           $this->db->insert('lat_long', array('latitude'=> $lat , 'longitude'=> $long));
    }


    public function searchDB($src_id,$dest_id,$distance,$duration)
    {
        
          $this->db->select()->from('distance_duration')->where(array('src_ref'=> $src_id , 'dest_ref'=> $dest_id , 'distance' => $distance , 'duration' => $duration))->limit(1);
          $query = $this->db->get();
          return $query->result();

    }


    public function insertDB($src_id,$dest_id,$distance,$duration)
    {
        
          $this->db->insert('distance_duration', array('src_ref'=> $src_id , 'dest_ref'=> $dest_id , 'distance' => $distance , 'duration' => $duration));

          return TRUE;

    }

	
}