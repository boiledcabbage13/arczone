<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Model\TblCustomerInfoModel;
use App\Http\Model\TblDailyTimeRecordModel;
use App\Http\Model\TblPointOfSaleModel;
use App\Http\Model\TblCustomerBalanceModel;
use App\Http\Model\TblDbVersionNumberModel;
use mysqli;
use DB;

class DBManipulationController extends Controller
{
	public static function check_if_insert_or_update($query)
	{
		$query= $query->count();
	}
	

	public static function create_dummy_tbl($tbl_to_create,$tbl_fields)
	{
		$servername = "";
		$username = "root";
		$password = "water123";
		$dbname = "arczone_db";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) 
		{
		    die("Connection failed: " . $conn->connect_error);
		} 
		else
		{

		 	$conn->query("DROP TABLE IF EXISTS ".$tbl_to_create.";");
	    	$sql = $tbl_fields;

			if ($conn->query($sql) === TRUE) 
			{
			    $result = 1;
			} 
			else 
			{
			    $result =  0;
			}
		
			
		}

		$conn->close();

		return $result;
	}
}
