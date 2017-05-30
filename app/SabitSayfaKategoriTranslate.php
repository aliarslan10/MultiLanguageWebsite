<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SabitSayfaKategoriTranslate extends Model
{
    protected $table = "sabit_sayfa_kategori_translate";

    protected $fillable = ['kategori', 'icerik', 'dil', 'link', 'menudeki_yeri', 'description'];

    protected $hidden = ['id'];
}