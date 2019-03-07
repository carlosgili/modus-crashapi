<?php

require '../vendor/autoload.php';

Class CrashInfo {

public static function GetRating( $vehicleid = "" ) {
	$fres = array( 'Count' => 0, 'Results' => array() );
	$surl = "https://one.nhtsa.gov/webapi/api/SafetyRatings/";
	$surl .= "VehicleId/" . $vehicleid;
	$surl .= "?format=json";
	$tres = file_get_contents( $surl );
	$tres = json_decode( $tres );
	$res = "Not Rated";
	if( is_object( $tres ) ) $res = $tres->Results[0]->OverallRating;
	return $res;
}

public static function GetCrashData( $myear, $make, $model, $rating = false ) {
	$fres = array( 'Count' => 0, 'Results' => array() );
	if( ($myear != "") and ($make != "") and ($model != "") ) {
		$surl = "https://one.nhtsa.gov/webapi/api/SafetyRatings";
		$surl .= "/modelyear/" . urlencode($myear);
		$surl .= "/make/" . urlencode($make);
		$surl .= "/model/" . urlencode($model);
		$surl .= "?format=json";
		$tres = file_get_contents( $surl );
		$tres = json_decode( $tres );
		if( is_object($tres) ) {
			$fres = array();
			$fres['Count'] = $tres->Count;
			$fres['Results'] = array();
			foreach( $tres->Results as $pos => $result ) {
				$fres['Results'][$pos] = array(
					'Description' => $result->VehicleDescription,
					'VechicleId' => $result->VehicleId
				);
				if( $rating == true ) {
					$fres['Results'][$pos]['CrashRating'] = CrashInfo::GetRating($result->VehicleId);
				}
			}
		}
	}
	return $fres;
}

}

Flight::route('/', function() {
	$request = Flight::request();
	header( 'content-type: application/json;charset=UTF-8' );
	print_r(json_encode(array('status'=>'Ready')));
});


Flight::route('/vehicles/*', function() {
	global $_GET;
	$ratings = false;
	if( $_GET['withRating'] == "true" ) $ratings = true;
	$request = Flight::request();
	if( $request->method == 'GET' ) {
		$url = $request->url;
		$url = split( "\?", $url );
		$url = $url[0];
		$url = split( "/", $url );
		$modelyear = urldecode($url[2]);
		$make = urldecode($url[3]);
		$model = urldecode($url[4]);
		if( $modelyear == "undefined" ) $modelyear = "";
		if( $make == "undefined" ) $make = "";
		if( $model == "undefined" ) $model = "";
		$fres = CrashInfo::GetCrashData( $modelyear, $make, $model, $ratings );
		header( 'content-type: application/json;charset=UTF-8' );
		print_r( json_encode( $fres ) );
	} else {
		$data = json_decode( $request->getBody() );
		if( is_object($data) ) {
			$modelyear = $data->modelYear;
			$make = $data->manufacturer;
			$model = $data->model;
			if( $modelyear == "undefined" ) $modelyear = "";
			if( $make == "undefined" ) $make = "";
			if( $model == "undefined" ) $model = "";
			$fres = CrashInfo::GetCrashData( $modelyear, $make, $model, $ratings );
			header( 'content-type: application/json;charset=UTF-8' );
			print_r( json_encode( $fres ) );
		} else {
			$fres = array( 'Count' => 0, 'Results' => array() );
			header( 'content-type: application/json;charset=UTF-8' );
			print_r( json_encode( $fres ) );
		}
	}
});


Flight::start();

