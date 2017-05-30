<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SabitSayfalarTranslate extends Model
{
    protected $table = "sabit_sayfalar_translate";

    protected $fillable = ['sabit_sayfalar_id', 'sayfa_adi', 'sayfa_icerik', 'dil', 'resim_linki', 'sayfa_linki', 'kisa_aciklama', 'description'];

    protected $hidden = ['id'];
}