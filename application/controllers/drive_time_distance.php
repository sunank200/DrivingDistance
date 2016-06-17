<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class drive_time_distance extends CI_CONTROLLER
{

  public function __construct() {
        parent::__construct();
        $this->load->model('drive_time_distance_model');
       
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
       
         $view_html = array(
            $this->load->view('drive_time_distance/header'),
            $this->load->view('drive_time_distance/index'),
            $this->load->view('drive_time_distance/footer')
            );
        return $view_html;
    }
    
    public function checkDB()
    {
    
      $ans[]=array();
       
        $source=$this->input->post('source');
        $destination=$this->input->post('destination');
        $is_latlong=$this->input->post('is_latlong');
        $ans["source_check"] = $source ;
       // $source="27.174865,78.00873860000002";
       // $destination="12.9715987,77.59456269999998";
       // $is_latlong = 1;
        $src_id = -1;
        $dest_id = -1;
        if($is_latlong == 0)
        {
            $val=$this->drive_time_distance_model->searchLocation($source); 
            if($val)
                $src_id = $val[0]->lat_long_reference ;


            $val=$this->drive_time_distance_model->searchLocation($destination); 
            if($val)
                $dest_id = $val[0]->lat_long_reference ;
        }
        else
        {

           // $src_array = explode(',',$source);
            //echo "src ".$source."  ".$src_array[0]." ".$src_array[1];
            $val=$this->drive_time_distance_model->searchReference($source); 
            if($val)
                $src_id = $val[0]->lat_long_reference ;

            //$dest_array = explode(',',$destination);
            
            $val=$this->drive_time_distance_model->searchReference($destination); 
            
            if($val)
                $dest_id = $val[0]->lat_long_reference ;

        }

            if($src_id != -1)
            $ans["src_id"] = $src_id;
            else
            $ans["src_id"] = -1;


            if($dest_id != -1)
            $ans["dest_id"] = $dest_id;
            else
            $ans["dest_id"] = -1;

          

           if($src_id != -1 && $dest_id!= -1)
           {

           $val=$this->drive_time_distance_model->checkDB($src_id,$dest_id);
           if($val)
           {

            $ans["status"]=1;
            $ans["message"] = "Present in DB";
            $ans["distance"]=$val[0]->distance;
            $ans["duration"]=$val[0]->duration;

           }
            else
           {

            $ans["status"]=0;
            $ans["message"] = "Not Present in DB";
           
           }
           }
           else
           {

            $ans["status"]=0;
            $ans["message"] = "Not Present in DB";
           
           }
       
         
            echo json_encode($ans);
            return;
         
    }

   
    public function insertDB()
    {
    

 
        $source=$this->input->post('source');
        $src_lat=$this->input->post('src_lat');
        $src_long=$this->input->post('src_long');
        $destination=$this->input->post('destination');
        $dest_lat=$this->input->post('dest_lat');
        $dest_long=$this->input->post('dest_long');
        $distance=$this->input->post('distance');
        $duration=$this->input->post('duration');
        $is_latlong=$this->input->post('is_latlong');

        $src_id = -1;
        $dest_id = -1;

      /*  $source="kazipet";
        $src_lat="17..0112";
         $src_long="17.044555";
         $destination="hyderabad";
         $dest_lat="19.7766";
         $dest_long="19.266262";
         $distance="123km";
         $duration="2 hours";
         $is_latlong = 0;*/

        if($is_latlong == 1)
        {

            $val=$this->drive_time_distance_model->searchReference($source); 
            if($val)
                $src_id = $val[0]->lat_long_reference ;
            else
            {
                 $val=$this->drive_time_distance_model->insertLatLong($src_lat,$src_long);
            }


            $val=$this->drive_time_distance_model->searchReference($destination); 
            if($val)
                $dest_id = $val[0]->lat_long_reference ;
            else
            {
                 $val=$this->drive_time_distance_model->insertLatLong($dest_lat,$dest_long);
            }



        }
        else
        {

            $val=$this->drive_time_distance_model->searchLatLong($src_lat,$src_long);
            if($val)
                $src_id = $val[0]->lat_long_reference ;
            else
            {
                 $val=$this->drive_time_distance_model->insertLatLong($src_lat,$src_long);
            } 


            $val=$this->drive_time_distance_model->searchLatLong($dest_lat,$dest_long);
            if($val)
                $dest_id = $val[0]->lat_long_reference ;
            else
            {
                 $val=$this->drive_time_distance_model->insertLatLong($dest_lat,$dest_long);
            } 




        }

        if($src_id ==-1)
        {

            $val=$this->drive_time_distance_model->searchLatLong($src_lat,$src_long);
            if($val)
                $src_id = $val[0]->lat_long_reference ;
        }
        if($dest_id ==-1)
        {
            $val=$this->drive_time_distance_model->searchLatLong($dest_lat,$dest_long);
            if($val)
                $dest_id = $val[0]->lat_long_reference ;

        }

        if($is_latlong == 0)
        {
              $val=$this->drive_time_distance_model->searchLocation($source);
              if(!$val)
              {
                 $val1=$this->drive_time_distance_model->insertLocation($source,$src_id);
              }

               $val=$this->drive_time_distance_model->searchLocation($destination);
              if(!$val)
              {
                 $val1=$this->drive_time_distance_model->insertLocation($destination,$dest_id);
              }
        }
        
       $val=$this->drive_time_distance_model->searchDB($src_id,$dest_id,$distance,$duration); 
       if(! $val)
       {
          $val1=$this->drive_time_distance_model->searchDB($dest_id,$src_id,$distance,$duration); 
            if(! $val1)
             $val=$this->drive_time_distance_model->insertDB($src_id,$dest_id,$distance,$duration); 
      }
        
          
            $ans[]=array();
            if($val == TRUE)
            $ans["status"]=1;
            else
            $ans["status"]=0;    
            echo json_encode($ans);
            return;
         
    }

  

}