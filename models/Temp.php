<?php namespace BeEasy\BlogExtension\Models;

use Model;

/**
 * temp Model
 */
class Temp extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'beeasy_blogextension_temps';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}