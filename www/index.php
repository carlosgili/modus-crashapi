<?php

require '../vendor/autoload.php';

Class HTTP {

public static function Get( $stUrl = '' ) {
  $desturl =  $stUrl;
  $ch = curl_init( $desturl );
  curl_setopt( $ch, CURLOPT_POST, 0);
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
  curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
  curl_setopt( $ch, CURLOPT_VERBOSE, false );
  curl_setopt( $ch, CURLOPT_FAILONERROR, true );
  $cookie_file = "/tmp/cookies";
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
  curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
  curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
  $res = curl_exec( $ch );
  if( $res === false ) {
    $res = curl_error( $ch );
  }
  curl_close( $ch );
  return $res;
}

}

Class CrashInfo {

public static function GetRating( $vehicleid = "" ) {
	$fres = array( 'Count' => 0, 'Results' => array() );
	$surl = "https://one.nhtsa.gov/webapi/api/SafetyRatings/";
	$surl .= "VehicleId/" . $vehicleid;
	$surl .= "?format=json";
	$tres = HTTP::Get( $surl );
	$tres = json_decode( $tres );
	return $tres->Results[0]->OverallRating;
}

public static function GetCrashData( $modelyear = "", $manufacturer = "", $model = "", $rating = false ) {
	$fres = array( 'Count' => 0, 'Results' => array() );
	$surl = "https://one.nhtsa.gov/webapi/api/SafetyRatings/";
	$surl .= "modelyear/" . $modelyear;
	$surl .= "/make/" . $manufacturer;
	$surl .= "/model/" . $model;
	$surl .= "?format=json";
	$tres = HTTP::Get( $surl );
	$tres = json_decode( $tres );
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
		array_shift( $url );
		$modelyear = $url[1];
		$manufacturer = $url[2];
		$model = $url[3];
		$fres = CrashInfo::GetCrashData( $modelyear, $manufacturer, $model, $ratings );
		header( 'content-type: application/json;charset=UTF-8' );
		print_r( json_encode( $fres ) );
	} else {
		$data = json_decode( $request->getBody() );
		$fres = CrashInfo::GetCrashData( $data->modelYear, $data->manufacturer, $data->model, $ratings );
		header( 'content-type: application/json;charset=UTF-8' );
		print_r( json_encode( $fres ) );
	}
});

Flight::start();

