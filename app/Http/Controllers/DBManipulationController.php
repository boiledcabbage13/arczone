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
	public static function create_tbl_dtr(Request $req)
	{
		$branch_id 	= json_decode($req->branch_id,true);

		$servername = "";
		$username = "root";
		$password = "water123";
		$dbname = "arczone_db";

		$table_create = "tbl_daily_time_record_".$branch_id;

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) 
		{
		    die("Connection failed: " . $conn->connect_error);
		} 
		else
		{

		 	$conn->query("DROP TABLE IF EXISTS ".$table_create.";");
	    	$sql = "CREATE TABLE ".$table_create."(
				  id int(11) unsigned NOT NULL AUTO_INCREMENT,
				  customer_id varchar(11) NOT NULL,
				  first_name varchar(50) NOT NULL,
				  time_in varchar(50) NOT NULL,
				  time_out varchar(50) NOT NULL,
				  date varchar(50) NOT NULL,
				  membership varchar(50) NOT NULL,
				  profit varchar(50) NOT NULL,
				  expected_time_out varchar(50) NOT NULL,
				  branch_id int(11) NOT NULL,
				  cashier_name varchar(50) NOT NULL,
				  pc_name varchar(50) NOT NULL,
				  pc_id int(11) DEFAULT NULL,
				  way_of_time_in varchar(50) NOT NULL,
				  upload_live int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

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
