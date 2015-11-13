<?php namespace App\Model\Record;

/**
 * Created by PhpStorm.
 * User: melon
 * Date: 7/7/15
 * Time: 3:29 PM
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    use SoftDeletes;
    protected $table = 'phones';
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'number', 'ext'];
}