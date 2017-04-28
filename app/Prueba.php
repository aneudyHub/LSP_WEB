<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{

    //protected $connection = 'telenord';
    protected $table = 'posts';

    protected $fillable = ['title', 'body'];

    protected $guarded = ['id'];


}