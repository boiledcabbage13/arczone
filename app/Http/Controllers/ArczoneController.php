<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DBManipulationController;


use App\Http\Model\TblCustomerInfoModel;
use App\Http\Model\TblDailyTimeRecordModel;
use App\Http\Model\TblPointOfSaleModel;
use App\Http\Model\TblCustomerBalanceModel;
use App\Http\Model\TblDbVersionNumberModel;


use mysqli;
use DB;


class ArczoneController extends Controller
{
	public static function foreach_tbl_condition_dtr($branch_id,$data_input,$local_to_live,$data_not_in_local)
	{
		$update_local_id = array();

		foreach ($data_input as $cus_dtr) 
		{
			$customer_daily_time_record_local['customer_id'] 			= $cus_dtr->customer_id;
			$customer_daily_time_record_local['first_name'] 			= $cus_dtr->first_name;
			$customer_daily_time_record_local['time_in'] 				= $cus_dtr->time_in;
			$customer_daily_time_record_local['time_out'] 				= $cus_dtr->time_out;
			$customer_daily_time_record_local['date'] 					= $cus_dtr->date;
			$customer_daily_time_record_local['membership'] 			= $cus_dtr->membership;
			$customer_daily_time_record_local['profit'] 				= $cus_dtr->profit;
			$customer_daily_time_record_local['expected_time_out'] 		= $cus_dtr->expected_time_out;
			$customer_daily_time_record_local['branch_id'] 				= $cus_dtr->branch_id;
			$customer_daily_time_record_local['cashier_name'] 			= $cus_dtr->cashier_name;
			$customer_daily_time_record_local['pc_name'] 				= $cus_dtr->pc_name;
			$customer_daily_time_record_local['pc_id'] 					= $cus_dtr->pc_id;
			$customer_daily_time_record_local['way_of_time_in'] 		= $cus_dtr->way_of_time_in;
			$customer_daily_time_record_local['upload_live'] 			= $cus_dtr->upload_live;
			DB::table('tbl_daily_time_record_'.$branch_id)->insert($customer_daily_time_record_local);

			array_push($update_local_id, $cus_dtr->id);//get local database id who insert in the database
		}

		//compare local table to live table and insert data to live table the result of comparison
		$union_local_to_live = DB::select($local_to_live);

		foreach ($union_local_to_live as $cus_dtr_live) 
		{
			$count_if_data_exists										= TblDailyTimeRecordModel::where([
																								['customer_id','=',$cus_dtr_live->customer_id],
																								['time_in','=',$cus_dtr_live->time_in]
																							])->count();
			
			//if data exist
			//	update
			//else
			//	insert
			if($count_if_data_exists > 0)
			{
				$update['customer_id'] 							= $cus_dtr_live->customer_id;
				$update['first_name'] 							= $cus_dtr_live->first_name;
				$update['time_in'] 								= $cus_dtr_live->time_in;
				$update['time_out'] 							= $cus_dtr_live->time_out;
				$update['date'] 								= $cus_dtr_live->date;
				$update['membership'] 							= $cus_dtr_live->membership;
				$update['profit'] 								= $cus_dtr_live->profit;
				$update['expected_time_out'] 					= $cus_dtr_live->expected_time_out;
				$update['branch_id'] 							= $cus_dtr_live->branch_id;
				$update['cashier_name'] 						= $cus_dtr_live->cashier_name;
				$update['pc_name'] 								= $cus_dtr_live->pc_name;
				$update['pc_id'] 								= $cus_dtr_live->pc_id;
				$update['way_of_time_in'] 						= $cus_dtr_live->way_of_time_in;
				$update['upload_live'] 							= $cus_dtr_live->upload_live;

				TblDailyTimeRecordModel::where([
													['customer_id','=',$cus_dtr_live->customer_id],
													['time_in','=',$cus_dtr_live->time_in]
												])->update($update);
			}
			else
			{
				$customer_daily_time_record                  		= new TblDailyTimeRecordModel;
				$customer_daily_time_record['customer_id'] 			= $cus_dtr_live->customer_id;
				$customer_daily_time_record['first_name'] 			= $cus_dtr_live->first_name;
				$customer_daily_time_record['time_in'] 				= $cus_dtr_live->time_in;
				$customer_daily_time_record['time_out'] 			= $cus_dtr_live->time_out;
				$customer_daily_time_record['date'] 				= $cus_dtr_live->date;
				$customer_daily_time_record['membership'] 			= $cus_dtr_live->membership;
				$customer_daily_time_record['profit'] 				= $cus_dtr_live->profit;
				$customer_daily_time_record['expected_time_out'] 	= $cus_dtr_live->expected_time_out;
				$customer_daily_time_record['branch_id'] 			= $cus_dtr_live->branch_id;
				$customer_daily_time_record['cashier_name'] 		= $cus_dtr_live->cashier_name;
				$customer_daily_time_record['pc_name'] 				= $cus_dtr_live->pc_name;
				$customer_daily_time_record['pc_id'] 				= $cus_dtr_live->pc_id;
				$customer_daily_time_record['way_of_time_in'] 		= $cus_dtr_live->way_of_time_in;
				$customer_daily_time_record['upload_live'] 			= $cus_dtr_live->upload_live;
				$customer_daily_time_record->save();
			}
		}

		//get all data not in dummy table and export it to local db
		$get_all_data_not_in_local = DB::select($data_not_in_local);

		$data['update_local_id'] = $update_local_id;
		$data['get_all_data_not_in_local'] = $get_all_data_not_in_local;

		return $data;
	}

	public static function foreach_tbl_condition_pos($branch_id,$data_input,$local_to_live,$data_not_in_local)
	{
		$update_local_id = array();

		foreach ($data_input as $cus_pos) 
		{
				$customer_point_of_sale_local['customer_id'] 		= $cus_pos->customer_id;				
				$customer_point_of_sale_local['first_name'] 		= $cus_pos->first_name;
				$customer_point_of_sale_local['membership'] 		= $cus_pos->membership;
				$customer_point_of_sale_local['purchased_time'] 	= $cus_pos->purchased_time;
				$customer_point_of_sale_local['purchased_date'] 	= $cus_pos->purchased_date;
				$customer_point_of_sale_local['purchased_item'] 	= $cus_pos->purchased_item;
				$customer_point_of_sale_local['consumed_item'] 		= $cus_pos->consumed_item;
				$customer_point_of_sale_local['quantity'] 			= $cus_pos->quantity;
				$customer_point_of_sale_local['way_of_purchased'] 	= $cus_pos->way_of_purchased;
				$customer_point_of_sale_local['price'] 				= $cus_pos->price;
				$customer_point_of_sale_local['get_load'] 			= $cus_pos->get_load;
				$customer_point_of_sale_local['branch_id'] 			= $cus_pos->branch_id;
				$customer_point_of_sale_local['cashier_name'] 		= $cus_pos->cashier_name;
				$customer_point_of_sale_local['upload_live'] 		= $cus_pos->upload_live;
				DB::table('tbl_point_of_sale_'.$branch_id)->insert($customer_point_of_sale_local);

				array_push($update_local_id, $cus_pos->id);//get local database id who insert in the database
		}

		//compare local table to live table and insert data to live table the result of comparison
		$union_local_to_live = DB::select($local_to_live);

		foreach ($union_local_to_live as $cus_pos_live) 
		{
			$count_if_data_exists				= TblPointOfSaleModel::where([
																			['customer_id','=',$cus_pos_live->customer_id],
																			['purchased_time','=',$cus_pos_live->purchased_time]
																			])->count();
			
			//if data exist
			//	update
			//else
			//	insert
			if($count_if_data_exists > 0)
			{
				$update['customer_id'] 			= $cus_pos_live->customer_id;				
				$update['first_name'] 			= $cus_pos_live->first_name;
				$update['membership'] 			= $cus_pos_live->membership;
				$update['purchased_time'] 		= $cus_pos_live->purchased_time;
				$update['purchased_date'] 		= $cus_pos_live->purchased_date;
				$update['purchased_item'] 		= $cus_pos_live->purchased_item;
				$update['consumed_item'] 		= $cus_pos_live->consumed_item;
				$update['quantity'] 			= $cus_pos_live->quantity;
				$update['way_of_purchased'] 	= $cus_pos_live->way_of_purchased;
				$update['price'] 				= $cus_pos_live->price;
				$update['get_load'] 			= $cus_pos_live->get_load;
				$update['branch_id'] 			= $cus_pos_live->branch_id;
				$update['cashier_name'] 		= $cus_pos_live->cashier_name;
				$update['upload_live'] 			= $cus_pos_live->upload_live;

				TblPointOfSaleModel::where([
											['customer_id','=',$cus_pos_live->customer_id],
											['purchased_time','=',$cus_pos_live->purchased_time]
											])->update($update);
			}
			else
			{
				$customer_point_of_sale                  		= new TblPointOfSaleModel;
				$customer_point_of_sale['customer_id'] 			= $cus_pos_live->customer_id;				
				$customer_point_of_sale['first_name'] 			= $cus_pos_live->first_name;
				$customer_point_of_sale['membership'] 			= $cus_pos_live->membership;
				$customer_point_of_sale['purchased_time'] 		= $cus_pos_live->purchased_time;
				$customer_point_of_sale['purchased_date'] 		= $cus_pos_live->purchased_date;
				$customer_point_of_sale['purchased_item'] 		= $cus_pos_live->purchased_item;
				$customer_point_of_sale['consumed_item'] 		= $cus_pos_live->consumed_item;
				$customer_point_of_sale['quantity'] 			= $cus_pos_live->quantity;
				$customer_point_of_sale['way_of_purchased'] 	= $cus_pos_live->way_of_purchased;
				$customer_point_of_sale['price'] 				= $cus_pos_live->price;
				$customer_point_of_sale['get_load'] 			= $cus_pos_live->get_load;
				$customer_point_of_sale['branch_id'] 			= $cus_pos_live->branch_id;
				$customer_point_of_sale['cashier_name'] 		= $cus_pos_live->cashier_name;
				$customer_point_of_sale['upload_live'] 			= $cus_pos_live->upload_live;				
				$customer_point_of_sale->save();
			}
		}

		//get all data not in dummy table and export it to local db
		$get_all_data_not_in_local = DB::select($data_not_in_local);

		$data['update_local_id'] = $update_local_id;
		$data['get_all_data_not_in_local'] = $get_all_data_not_in_local;

		return $data;

	}

	public static function foreach_tbl_condition_cus_bal($branch_id,$data_input,$local_to_live,$data_not_in_local)
	{
		$update_local_id = array();

		foreach ($data_input as $cus_bal) 
		{
			$customer_balance_local['customer_id']			= $cus_bal->customer_id;
			$customer_balance_local['customer_balance']		= $cus_bal->customer_balance;
			$customer_balance_local['customer_points']		= $cus_bal->customer_points;
			$customer_balance_local['time']					= $cus_bal->time;
			$customer_balance_local['date']					= $cus_bal->date;
			$customer_balance_local['description']			= $cus_bal->description;
			$customer_balance_local['upload_live']			= $cus_bal->upload_live;
			DB::table('tbl_customer_balance_'.$branch_id)->insert($customer_balance_local);

			array_push($update_local_id, $cus_bal->id);//get local database id who insert in the database
		}

		//compare local table to live table and insert data to live table the result of comparison
		$union_local_to_live = DB::select($local_to_live);

		foreach ($union_local_to_live as $cus_bal_live) 
		{
			$count_if_data_exists										= TblCustomerBalanceModel::where([
																								['customer_id','=',$cus_bal_live->customer_id],
																								['time','=',$cus_bal_live->time]
																							])->count();
			
			//if data exist
			//	update
			//else
			//	insert
			if($count_if_data_exists > 0)
			{
				$update['customer_id']			= $cus_bal_live->customer_id;
				$update['customer_balance']		= $cus_bal_live->customer_balance;
				$update['customer_points']		= $cus_bal_live->customer_points;
				$update['time']					= $cus_bal_live->time;
				$update['date']					= $cus_bal_live->date;
				$update['description']			= $cus_bal_live->description;
				$update['upload_live']			= $cus_bal_live->upload_live;		

				TblCustomerBalanceModel::where([
													['customer_id','=',$cus_bal_live->customer_id],
													['time','=',$cus_bal_live->time]
												])->update($update);
			}
			else
			{
				$customer_balance                  			= new TblCustomerBalanceModel;
				$customer_balance['customer_id']			= $cus_bal_live->customer_id;
				$customer_balance['customer_balance']		= $cus_bal_live->customer_balance;
				$customer_balance['customer_points']		= $cus_bal_live->customer_points;
				$customer_balance['time']					= $cus_bal_live->time;
				$customer_balance['date']					= $cus_bal_live->date;
				$customer_balance['description']			= $cus_bal_live->description;
				$customer_balance['upload_live']			= $cus_bal_live->upload_live;				
				$customer_balance->save();
			}
		}

		//get all data not in dummy table and export it to local db
		$get_all_data_not_in_local = DB::select($data_not_in_local);

		$data['update_local_id'] = $update_local_id;
		$data['get_all_data_not_in_local'] = $get_all_data_not_in_local;

		return $data;

	}

	public static function foreach_tbl_condition_cus_info($branch_id,$data_input,$local_to_live,$data_not_in_local)
	{
		$update_local_id = array();

		foreach ($data_input as $cus_info)
		{
			$customer_info_local['customer_id']  		= $cus_info->customer_id; 
			$customer_info_local['first_name'] 			= $cus_info->first_name;
			$customer_info_local['last_name']  			= $cus_info->last_name; 
			$customer_info_local['gender'] 				= $cus_info->gender;
			$customer_info_local['birthday'] 			= $cus_info->birthday;
			$customer_info_local['contact_number']  	= $cus_info->contact_number; 
			$customer_info_local['address']  			= $cus_info->address; 
			$customer_info_local['balance']  			= $cus_info->balance; 
			$customer_info_local['points'] 				= $cus_info->points;
			$customer_info_local['membership'] 			= $cus_info->membership;
			$customer_info_local['user_level']  		= $cus_info->user_level; 
			$customer_info_local['password']   			= $cus_info->password;  
			$customer_info_local['profile_picture']  	= $cus_info->profile_picture; 
			$customer_info_local['member_create']  		= $cus_info->member_create; 
			$customer_info_local['upload_live'] 		= $cus_info->upload_live;
			DB::table('tbl_customer_info_'.$branch_id)->insert($customer_info_local);

			array_push($update_local_id, $cus_info->id);//get local database id who insert in the database
		}

		//compare local table to live table and insert data to live table the result of comparison
		$union_local_to_live = DB::select($local_to_live);

		foreach ($union_local_to_live as $cus_info_live) 
		{
			$count_if_data_exists										= TblCustomerInfoModel::where([
																								['customer_id','=',$cus_info_live->customer_id],
																								['member_create','=',$cus_info_live->member_create]
																							])->count();
			
			//if data exist
			//	update
			//else
			//	insert
			if($count_if_data_exists > 0)
			{
				$update['customer_id']  		= $cus_info_live->customer_id; 
				$update['first_name'] 		= $cus_info_live->first_name;
				$update['last_name']  		= $cus_info_live->last_name; 
				$update['gender'] 			= $cus_info_live->gender;
				$update['birthday'] 			= $cus_info_live->birthday;
				$update['contact_number']  	= $cus_info_live->contact_number; 
				$update['address']  			= $cus_info_live->address; 
				$update['balance']  			= $cus_info_live->balance; 
				$update['points'] 			= $cus_info_live->points;
				$update['membership'] 		= $cus_info_live->membership;
				$update['user_level']  		= $cus_info_live->user_level; 
				$update['password']   		= $cus_info_live->password;  
				$update['profile_picture']  	= $cus_info_live->profile_picture; 
				$update['member_create']  	= $cus_info_live->member_create; 
				$update['upload_live'] 		= $cus_info_live->upload_live;		

				TblCustomerInfoModel::where([
													['customer_id','=',$cus_info_live->customer_id],
													['member_create','=',$cus_info_live->member_create]
												])->update($update);
			}
			else
			{
				$customer_info                  	= new TblCustomerInfoModel;
				$customer_info['customer_id']  		= $cus_info_live->customer_id; 
				$customer_info['first_name'] 		= $cus_info_live->first_name;
				$customer_info['last_name']  		= $cus_info_live->last_name; 
				$customer_info['gender'] 			= $cus_info_live->gender;
				$customer_info['birthday'] 			= $cus_info_live->birthday;
				$customer_info['contact_number']  	= $cus_info_live->contact_number; 
				$customer_info['address']  			= $cus_info_live->address; 
				$customer_info['balance']  			= $cus_info_live->balance; 
				$customer_info['points'] 			= $cus_info_live->points;
				$customer_info['membership'] 		= $cus_info_live->membership;
				$customer_info['user_level']  		= $cus_info_live->user_level; 
				$customer_info['password']   		= $cus_info_live->password;  
				$customer_info['profile_picture']  	= $cus_info_live->profile_picture; 
				$customer_info['member_create']  	= $cus_info_live->member_create; 
				$customer_info['upload_live'] 		= $cus_info_live->upload_live;				
				$customer_info->save();
			}
		}

		//get all data not in dummy table and export it to local db
		$get_all_data_not_in_local = DB::select($data_not_in_local);

		$data['update_local_id'] = $update_local_id;
		$data['get_all_data_not_in_local'] = $get_all_data_not_in_local;

		return $data;

	}



	public static function insert_local_to_dummy_tbl($ref,$branch_id,$data_input,$tbl_to_create,$tbl_fields)
	{
		$data_input = json_decode($data_input);

		$check = DBManipulationController::create_dummy_tbl($tbl_to_create,$tbl_fields);
		
		switch ($ref) 
		{
			case 'dtr':
				$local_to_live = "SELECT * 
								FROM (select * from tbl_daily_time_record 
								UNION ALL
								select * from tbl_daily_time_record_".$branch_id.")tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_daily_time_record where tbl_select_data.time_out = tbl_daily_time_record.time_out)
								or NOT EXISTS (Select * from tbl_daily_time_record where tbl_select_data.time_in = tbl_daily_time_record.time_in)
								or NOT EXISTS (Select * from tbl_daily_time_record where tbl_select_data.customer_id = tbl_daily_time_record.customer_id)";
				$data_not_in_local = "SELECT * 
								FROM (select * from tbl_daily_time_record_".$branch_id." 
								UNION ALL
								select * from tbl_daily_time_record)tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_daily_time_record_".$branch_id." where tbl_select_data.time_out = tbl_daily_time_record_".$branch_id.".time_out)
								or NOT EXISTS (Select * from tbl_daily_time_record_".$branch_id." where tbl_select_data.time_in = tbl_daily_time_record_".$branch_id.".time_in)
								or NOT EXISTS (Select * from tbl_daily_time_record_".$branch_id." where tbl_select_data.customer_id = tbl_daily_time_record_".$branch_id.".customer_id)";

				$data['tbl_result_foreach'] = Self::foreach_tbl_condition_dtr($branch_id,$data_input,$local_to_live,$data_not_in_local);
				break;

			case 'pos':
				$local_to_live = "SELECT * 
								FROM (select * from tbl_point_of_sale 
								UNION ALL
								select * from tbl_point_of_sale_".$branch_id.")tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_point_of_sale where tbl_select_data.purchased_time = tbl_point_of_sale.purchased_time)
								or NOT EXISTS (Select * from tbl_point_of_sale where tbl_select_data.customer_id = tbl_point_of_sale.customer_id)
								or NOT EXISTS (Select * from tbl_point_of_sale where tbl_select_data.price = tbl_point_of_sale.price)
								or NOT EXISTS (Select * from tbl_point_of_sale where tbl_select_data.get_load= tbl_point_of_sale.get_load)";
				$data_not_in_local = "SELECT * 
								FROM (select * from tbl_point_of_sale_".$branch_id."
								UNION ALL
								select * from tbl_point_of_sale)tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_point_of_sale_".$branch_id." where tbl_select_data.purchased_time = tbl_point_of_sale_".$branch_id.".purchased_time)
								or NOT EXISTS (Select * from tbl_point_of_sale_".$branch_id." where tbl_select_data.customer_id = tbl_point_of_sale_".$branch_id.".customer_id)
								or NOT EXISTS (Select * from tbl_point_of_sale_".$branch_id." where tbl_select_data.price = tbl_point_of_sale_".$branch_id.".price)
								or NOT EXISTS (Select * from tbl_point_of_sale_".$branch_id." where tbl_select_data.get_load= tbl_point_of_sale_".$branch_id.".get_load)";

				$data['tbl_result_foreach'] = Self::foreach_tbl_condition_pos($branch_id,$data_input,$local_to_live,$data_not_in_local);
				break;

			case 'cus_bal':
				$local_to_live = "SELECT * 
								FROM (select * from tbl_customer_balance 
								UNION ALL
								select * from tbl_customer_balance_".$branch_id.")tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_customer_balance where tbl_select_data.time = tbl_customer_balance.time)
								or NOT EXISTS (Select * from tbl_customer_balance where tbl_select_data.customer_id = tbl_customer_balance.customer_id)
								or NOT EXISTS (Select * from tbl_customer_balance where tbl_select_data.customer_balance = tbl_customer_balance.customer_balance)
								or NOT EXISTS (Select * from tbl_customer_balance where tbl_select_data.customer_points = tbl_customer_balance.customer_points)";
				$data_not_in_local = "SELECT * 
								FROM (select * from tbl_customer_balance_".$branch_id."
								UNION ALL
								select * from tbl_customer_balance)tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_customer_balance_".$branch_id." where tbl_select_data.time = tbl_customer_balance_".$branch_id.".time)
								or NOT EXISTS (Select * from tbl_customer_balance_".$branch_id." where tbl_select_data.customer_id = tbl_customer_balance_".$branch_id.".customer_id)
								or NOT EXISTS (Select * from tbl_customer_balance_".$branch_id." where tbl_select_data.customer_balance = tbl_customer_balance_".$branch_id.".customer_balance)
								or NOT EXISTS (Select * from tbl_customer_balance_".$branch_id." where tbl_select_data.customer_points = tbl_customer_balance_".$branch_id.".customer_points)";

				$data['tbl_result_foreach'] = Self::foreach_tbl_condition_cus_bal($branch_id,$data_input,$local_to_live,$data_not_in_local);
				break;

			case 'cus_info':
				$local_to_live = "SELECT * 
								FROM (select * from tbl_customer_info 
								UNION ALL
								select * from tbl_customer_info_".$branch_id.")tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_customer_info where tbl_select_data.customer_id = tbl_customer_info.customer_id)
								or NOT EXISTS (Select * from tbl_customer_info where tbl_select_data.membership = tbl_customer_info.membership)";
				$data_not_in_local = "SELECT * 
								FROM (select * from tbl_customer_info_".$branch_id."
								UNION ALL
								select * from tbl_customer_info)tbl_select_data
								WHERE NOT EXISTS (Select * from tbl_customer_info_".$branch_id." where tbl_select_data.customer_id = tbl_customer_info_".$branch_id.".customer_id)
								or NOT EXISTS (Select * from tbl_customer_info_".$branch_id." where tbl_select_data.membership = tbl_customer_info_".$branch_id.".membership)";

				$data['tbl_result_foreach'] = Self::foreach_tbl_condition_cus_info($branch_id,$data_input,$local_to_live,$data_not_in_local);
				break;
			
			default:
				# code...
				break;
		}
		

		return $data;

	}

	public function insert_local_to_dummy_tbl_compare(Request $req)
	{
		$branch_id = json_decode($req->branch_id,true);
		$data['data_input'] = json_decode($req->data_input,true);

		$data_result['tbl_daily_time_record_result'] = Self::insert_local_to_dummy_tbl(
																						"dtr",
																						$branch_id,$data['data_input']['tbl_daily_time_record'],
																						"tbl_daily_time_record_".$branch_id,
																						"CREATE TABLE tbl_daily_time_record_".$branch_id.
																						"(
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
																						) 
																						ENGINE=InnoDB DEFAULT CHARSET=latin1;"
																					);
		$data_result['tbl_point_of_sale_result'] = Self::insert_local_to_dummy_tbl(
																						"pos",
																						$branch_id,$data['data_input']['tbl_point_of_sale'],
																						"tbl_point_of_sale_".$branch_id,
																						"CREATE TABLE tbl_point_of_sale_".$branch_id. 
																						"(
																						  id int(11) NOT NULL AUTO_INCREMENT,
																						  customer_id varchar(50) NOT NULL,
																						  first_name varchar(50) NOT NULL,
																						  membership varchar(50) NOT NULL,
																						  purchased_time varchar(50) NOT NULL,
																						  purchased_date varchar(50) NOT NULL,
																						  purchased_item varchar(50) NOT NULL,
																						  consumed_item varchar(50) NOT NULL,
																						  quantity varchar(11) NOT NULL,
																						  way_of_purchased varchar(50) NOT NULL,
																						  price int(11) NOT NULL,
																						  get_load int(11) NOT NULL,
																						  branch_id int(11) NOT NULL,
																						  cashier_name varchar(50) NOT NULL,
																						  upload_live int(11) NOT NULL DEFAULT '0',
																						  PRIMARY KEY (id)
																						) 
																						ENGINE=InnoDB DEFAULT CHARSET=latin1;"
																					);

		$data_result['tbl_customer_balance_result'] = Self::insert_local_to_dummy_tbl(
																						"cus_bal",
																						$branch_id,$data['data_input']['tbl_customer_balance'],
																						"tbl_customer_balance_".$branch_id,
																						"CREATE TABLE tbl_customer_balance_".$branch_id. 
																						"(
																						  id int(11) NOT NULL AUTO_INCREMENT,
																						  customer_id varchar(11) NOT NULL,
																						  customer_balance int(11) NOT NULL,
																						  customer_points int(11) NOT NULL,
																						  time varchar(50) NOT NULL,
																						  date varchar(50) NOT NULL,
																						  description varchar(50) NOT NULL,
																						  upload_live int(11) NOT NULL DEFAULT '0',
																						  PRIMARY KEY (id)
																						) 
																						ENGINE=InnoDB DEFAULT CHARSET=latin1;"
																					);

		$data_result['tbl_customer_info_result'] = Self::insert_local_to_dummy_tbl(
																						"cus_info",
																						$branch_id,$data['data_input']['tbl_customer_info'],
																						"tbl_customer_info_".$branch_id,
																						"CREATE TABLE tbl_customer_info_".$branch_id. 
																						"(
																						  id int(11) NOT NULL AUTO_INCREMENT,
																						  customer_id varchar(11) NOT NULL,
																						  first_name varchar(50) NOT NULL,
																						  last_name varchar(50) NOT NULL,
																						  gender varchar(50) NOT NULL,
																						  birthday varchar(50) NOT NULL,
																						  contact_number varchar(50) NOT NULL,
																						  address varchar(100) NOT NULL,
																						  balance int(11) DEFAULT NULL,
																						  points int(11) DEFAULT NULL,
																						  membership varchar(50) NOT NULL,
																						  user_level int(11) NOT NULL,
																						  password varchar(50) NOT NULL,
																						  profile_picture mediumblob,
																						  member_create varchar(50) NOT NULL,
																						  upload_live int(11) NOT NULL DEFAULT '0',
																						  PRIMARY KEY (id),
																						  UNIQUE KEY customer_id (customer_id)
																						) 
																						ENGINE=InnoDB DEFAULT CHARSET=latin1;"
																					);



		return $data_result;
	}
}



