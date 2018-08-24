<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class TblDbVersionNumberModel extends Model
{
    protected $table = 'tbl_db_version_number';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
