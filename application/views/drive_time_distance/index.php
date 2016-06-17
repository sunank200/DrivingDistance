<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyB6ky0s6kmaxH15hsxsNHKuZeI6n_OG2eA"></script>
    <script type="text/javascript">
        var source, destination;
        var src_lat=0.0,src_long=0.0,dest_lat=0.0,dest_long=0.0;
        
       $(document).ready(function(){
	    $("#sub1").click(function(){
	           
            source = document.getElementById("txtSource").value;
            destination = document.getElementById("txtDestination").value;
            var is_latlong = 0;
            var src_array=source.split(',',source);
            var src_lat=parseFloat(src_array[0]);
            var src_long=parseFloat(src_array[1]);

            console.log((typeof src_lat));
            console.log((typeof src_long));
           
            if(src_array.length == 2 && (typeof src_lat)=="number" && (typeof src_long)=="number" )
            is_latlong = 1;

             console.log("CHECKDB: islatlong "+is_latlong);

             $.ajax({
	        	url: "index.php/drive_time_distance/checkDB", 
	        	type: "POST",
                        data: {'source': source, 'destination': destination, 'is_latlong' : is_latlong},
	        	success: function(data){
	        		data= JSON.parse(data);
	        		//console.log("success");
	        		//console.log(data);
	        		console.log(data["source_check"]);
	        		console.log(data["message"]);
	        		console.log("src_id "+data["src_id"]);
	        		console.log("dest_id "+data["dest_id"]);
	                if( data["status"] == 1)
	        		{
	        			  var dvDistance = document.getElementById("dvDistance");
                          dvDistance.innerHTML = "";
                          dvDistance.innerHTML += "Distance: " + data["distance"] + "<br />";
                          dvDistance.innerHTML += "Duration:" + data["duration"];         
	        		}
	        		else
	        		{
	        			if(is_latlong == 0)
	        			{
	        			   GetLatLong(source,1);
	        			   GetLatLong(destination,2);

	        			}
	        			GetRoute(is_latlong);   	
	        		}
	            },
	            error: function (jqXHR, textStatus, errorThrown)
				{		
					console.log("Error:Not checked properly");
					return false;
				}
	      });
	     });
	    });

        function GetLatLong(source, flag)
        {

        	var geocoder = new google.maps.Geocoder();
            var address = source;
            //console.log("entered in getlatlong");
            geocoder.geocode({ 'address': address }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                	if(flag==1)
                	{
                      src_lat = results[0].geometry.location.lat();
                      src_long = results[0].geometry.location.lng();
                     // console.log("Latitude: " + src_lat + "\nLongitude: " + src_lat);
                    }
                    else
                    {
                        dest_lat = results[0].geometry.location.lat();
                        dest_long = results[0].geometry.location.lng();
                       // console.log("Latitude: " + dest_lat + "\nLongitude: " + dest_long);
                    }

                    return true;

                } else {
                   console.log("No values received");
                   return false;
                }
            });

           
        }
          
       
         function GetRoute(is_latlong) {
      
            // source = document.getElementById("txtSource").value;
            // destination = document.getElementById("txtDestination").value;
            
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [source],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false
            }, function (response, status) {
                if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
                    //console.log(response);
                    var distance = response.rows[0].elements[0].distance.text;
                    var duration = response.rows[0].elements[0].duration.text;
                    var dvDistance = document.getElementById("dvDistance");
                    dvDistance.innerHTML = "";
                    dvDistance.innerHTML += "Distance: " + distance + "<br />";
                    dvDistance.innerHTML += "Duration:" + duration;
                   

                  // console.log("in getroute src_lat "+ src_lat);

                $.ajax({
	        	url: "index.php/drive_time_distance/insertDB", 
	        	type: "POST",
                data: {'source': source, 'destination': destination, 'is_latlong' : is_latlong,'src_lat':src_lat,'src_long':src_long,'dest_lat':dest_lat,'dest_long':dest_long ,'distance' : distance, "duration" : duration},
	        	success: function(data){
	        		data= JSON.parse(data);
	        		console.log("Src_Lat: "+src_lat);
	        		console.log("Src_Long: "+src_long);
	        		console.log("success");
	        		//console.log("success");
	        		//console.log(data);
	                if( data["status"] == 1)
	        		{
	        			console.log("succesfully inserted");      
	        		}
	        		else
	        		{
	        			console.log("check val "+data["val"]);
	        			//console.log("check lat "+data["src_lat"]);
	        			//console.log("check long "+data["src_long"]);
	        			//console.log("db error : could not insert");
	        		}
	            },
	            error: function (jqXHR, textStatus, errorThrown)
				{		
					console.log("Not inserted:some error occured");
					//showAlert("Some Error occured! Please reload/refresh the page and try again.");
					return false;
				}
	      });

                } else {
                    alert("Unable to find the distance via road.");
                }
            });
        }

        
    </script>
<div class="container">
<h2>Enter Origin and Destination</h2>

  <table border="0" cellpadding="0" cellspacing="3">
        <tr>
            <td colspan="2">
                Source:
                <input type="text" id="txtSource" placeholder="Enter Source" style="width: 200px" />
                &nbsp; Destination:
                <input type="text" id="txtDestination" placeholder="Enter Destination" style="width: 200px" />
               
                <br />
                <br />
                <p>Enter the address of source or destination or their Latitude and Longitude:</p>
                <p>Example for Kazipet: 17.972366, 79.50345</p>

                <input type="button" value="Search" id="sub1"  />
                <hr />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="dvDistance">
                </div>
            </td>
        </tr>
       
    </table>
    
     <div id="div1"></div>
</div>

