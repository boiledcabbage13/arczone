<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class TblCustomerInfoModel extends Model
{
    protected $table = 'tbl_customer_info';
    protected $primaryKey = 'id	';
    public $timestamps = false;
}

