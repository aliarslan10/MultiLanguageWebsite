<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkRecords extends Model
{
    protected $table = "link_records";

    protected $fillable = ['eski_link', 'yeni_link', 'onceki_id'];

    protected $hidden = ['id'];
}