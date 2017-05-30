<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use App\SabitSayfalar; //ana menü
use App\SabitSayfalarTranslate; //ana menü
use App\SabitSayfaKategori; //ana menü
use App\SabitSayfaKategoriTranslate; //ana menü
use App\SiteAyarlari;
use App\ChatOdalari;
use App\BlogIcerik;
use App\TopluMailSablonu;
use App\UrunOzellikleri;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $language = explode("/", \Request::path()); # path() => Ana URL'den sonrasını alıyor. PUBLIC olup olmaması önemli değil.
        
        if($language['0'] == "en" || $language['0'] == "ar" || $language['0'] == "fr" || $language['0'] == "de"){
            
            App::setLocale($language['0']);

            $kategori = SabitSayfaKategori::select('sabit_sayfa_kategori_translate.*','menudeki_yeri','alt_menu','sira','durum')
                        ->where('durum','=','1')->orderBy('sira')
                        ->rightJoin('sabit_sayfa_kategori_translate','sabit_sayfa_kategori_translate.sabit_sayfa_kategori_id','=','sabit_sayfa_kategori.id')
                        ->where('sabit_sayfa_kategori_translate.dil','=',App::getLocale())->get();

           
            $altKategoriler = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')->where('anasayfada_goster','=','1')
                            ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())
                            ->where('kategori_id','=','3')->orWhere('kategori_id','=','2')                            
                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->get();


            $tumurunler = SabitSayfalar::select('sabit_sayfalar.*','sabit_sayfalar_translate.*')
            ->whereNull('sabit_sayfalar.ust_sayfa')->where('durum','=','1')->where('sabit_sayfalar.kategori_id','=','3')
            ->join('sabit_sayfalar_translate', 'sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->get();
            

            /* ###################### TRANSLATE TABLOLARI BAZ ALINDI ######################

            $kategori = SabitSayfaKategoriTranslate::select(
                'sabit_sayfa_kategori_translate.*',
                'sabit_sayfa_kategori.menudeki_yeri','sabit_sayfa_kategori.alt_menu','sabit_sayfa_kategori.sira','sabit_sayfa_kategori.durum')
            ->where('dil','=',App::getLocale())
            ->where('sabit_sayfa_kategori.durum','=','1')
            ->join('sabit_sayfa_kategori','sabit_sayfa_kategori.id','=','sabit_sayfa_kategori_translate.sabit_sayfa_kategori_id')->get();

            $altKategoriler = SabitSayfalarTranslate::select(
                'sabit_sayfalar_translate.*',
                'sabit_sayfalar.durum', 'sabit_sayfalar.kategori_id', 'sabit_sayfalar.ust_sayfa','sabit_sayfalar.anasayfada_goster','sabit_sayfalar.menudeki_yeri')
             ->leftJoin('sabit_sayfalar','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
             ->where('sabit_sayfalar.durum','=','1')
             ->whereNull('sabit_sayfalar.ust_sayfa')
             ->where('sabit_sayfalar.kategori_id','=','3')
             ->orWhere('sabit_sayfalar.kategori_id','=','2')
             ->where('sabit_sayfalar.anasayfada_goster','=','1')->where('sabit_sayfalar_translate.dil','=',App::getLocale())->get();


            */ ###################### ###################### ###################### ############
        
        }else{
            App::setLocale("tr");
        
            // $firsaturunleri = UrunOzellikleri::where('aktiflik','=','1')->where('firsat_urunu','=','1')->join('sabit_sayfalar', 'sabit_sayfalar.id','=','urun_ozellikleri.urun_id')->get();

            $kategori = SabitSayfaKategori::where('durum','=','1')->orderBy('sira')->get();

            /**********************************************************************
            * blade.php kısmında sabit_sayfa_kategori_id değişkeni tanımlı ve bu değişken sadece translate uzantılı tablolarda var.
            * O yüzden bunu yapmak zorunda kaldım.
            ************************************************************************/

            foreach ($kategori as $key => $value) {
                $kategori[$key]['sabit_sayfa_kategori_id'] = $value->id;
            }

          

            $altKategoriler = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')->where('kategori_id','=','3')
                                                                   ->orWhere('kategori_id','=','2')->where('anasayfada_goster','=','1')->get();



            $tumurunler = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')->where('sabit_sayfalar.kategori_id','=','3')->get();
            

            //dd($kategori);
            //$blogSlider = BlogIcerik::where('durum','=','1')->orderBy('id','desc')->take(2)->get();
            //$haberler = BlogIcerik::where('kategori_id','=','2')->where('durum','=','1')->orderBy('id','desc')->take(2)->get();
           
            //$duyurular = BlogIcerik::where('kategori_id','=','2')->where('durum','=','1')->orderBy('id','desc')->take(1)->get();
            

           // $vitrin = Vitrin::where('durum','=','1')->orderby('id')->get();
            //$mailSablonu = TopluMailSablonu::all();

        }
        
            // Actually, You will take all content from database. After that, you will make your array what options you want.
            $ayar = SiteAyarlari::findorfail(1);
            $menu = SabitSayfalar::where('durum','=','1')->get();

        //dd($tumurunler[0]['sayfa_adi']);
        //view()->share('blogs', $blogSlider);
        //view()->share('haberler', $haberler);
        view()->share('ayarlar', $ayar);
        view()->share('menuler', $menu);
        view()->share('tumurunler', $tumurunler);
        view()->share('urunKategorileri', $altKategoriler);
       // view()->share('firsaturunleri', $firsaturunleri);
        view()->share('kategoriler', $kategori);
       // view()->share('vitrinler', $vitrin);
        //view()->share('mailSablonu', $mailSablonu);
		//view()->share('desktop',"<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
