<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddGeodistanceStoredProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	$procedure = "
    	CREATE FUNCTION GeoDist( lat1 DOUBLE, lon1 DOUBLE, lat2 DOUBLE, lon2 DOUBLE ,earthRadius double) RETURNS DOUBLE 
BEGIN 
  DECLARE pi, q1, q2, q3 DOUBLE; 
  DECLARE rads DOUBLE DEFAULT 0; 
  SET pi = PI(); 
  SET lat1 = lat1 * pi / 180; 
  SET lon1 = lon1 * pi / 180; 
  SET lat2 = lat2 * pi / 180; 
  SET lon2 = lon2 * pi / 180; 
  SET q1 = COS(lon1-lon2); 
  SET q2 = COS(lat1-lat2); 
  SET q3 = COS(lat1+lat2); 
  SET rads = ACOS( 0.5*((1.0+q1)*q2 - (1.0-q1)*q3) );  
  RETURN earthRadius * rads; 
END;

DROP PROCEDURE IF EXISTS sitesInRadius; 
CREATE PROCEDURE sitesInRadius( lat DOUBLE, lng DOUBLE,distance double,earthRadius double) 
BEGIN 
	/* calculates de boundaries */
    declare min_lat, max_lat,min_lng,max_lng double;
	set @cardinal_north = 0, @cardinal_south = 180, @cardinal_east= 90, @cardinal_west = 270;
 
	
 
	set @rLat = radians(lat);
	set @rLng = radians(lng);
	set @rAngDist = distance / earthRadius;
    
    /* min_lat */
	set @rAngle = radians(@cardinal_south);
	set @rLatB = asin(sin(@rLat) * cos(@rAngDist) + cos(@rLat) * sin(@rAngDist) * cos(@rAngle));
	set min_lat = degrees(@rLatB);
    
    /* max_lat */
    set @rAngle = radians(@cardinal_north);
	set @rLatB = asin(sin(@rLat) * cos(@rAngDist) + cos(@rLat) * sin(@rAngDist) * cos(@rAngle));
	set max_lat = degrees(@rLatB);
    
    /* min_lng */
	set @rAngle = radians(@cardinal_west);
	set	@rLonB = @rLng + atan2(sin(@rAngle) * sin(@rAngDist) * cos(@rLat), cos(@rAngDist) - sin(@rLat) * sin(@rLatB));
	set min_lng = degrees(@rLonB);
    
    /* max_lng */
    set @rAngle = radians(@cardinal_east);
	set	@rLonB = @rLng + atan2(sin(@rAngle) * sin(@rAngDist) * cos(@rLat), cos(@rAngDist) - sin(@rLat) * sin(@rLatB));
	set max_lng = degrees(@rLonB);
	
    select GeoDist(lat,lng,sites.lat,sites.lon,earthRadius) as site_distance,sites.* from sites where sites.lat>= min_lat and sites.lat<=max_lat and sites.lon>=min_lng and sites.lon<=max_lng
    having site_distance <=distance;
    
END;";
    	$this->down();
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	$procedure="
					DROP FUNCTION IF EXISTS GeoDist;
					DROP PROCEDURE IF EXISTS sitesInRadius;
					";
	    DB::unprepared($procedure);
    }
}
