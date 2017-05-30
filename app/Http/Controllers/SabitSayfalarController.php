<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SabitSayfalar;
use App\SabitSayfalarTranslate;
use App\SabitSayfaKategori;
use xcrud;
use App;
use App\BlogIcerik;
use App\Resimler;
use App\LinkRecords;
use App\IletisimBilgileri;

include('xcrud/xcrud/xcrud.php'); //public klasöründe

class SabitSayfalarController extends Controller
{
	public function goruntule($dil=null, $id=null)
	{
        $kategori_kontrol = SabitSayfaKategori::where('link',$id)->get();
        
        if(App::getLocale() != "tr"){
       		

       		$id = App::getLocale() . "/" . $id;
            
            $menu = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')
                                ->where('kategori_id','=','2')->where('anasayfada_goster','=','1')
                                ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
                                ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->get();

            $sayfaicerik = SabitSayfalar::where('durum','=','1')
		                                ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
            							->where('sabit_sayfalar_translate.sayfa_linki',$id)->get();

        }else{

        	$id = $dil;
        	$menu = SabitSayfalar::where('kategori_id','=','2')->where('durum','=','1')->get();
        	$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$id)->orderby('id')->get();
        }

		return view('kurumsal.kurumsal_icerik')->with('getLink', $id)
											   ->with('sayfaicerik',$sayfaicerik)
											   ->with('menu',$menu);
	}

	public function hizmetler($hizmet_adi=null)
    {	
    	$hizmet = SabitSayfalar::where('kategori_id','=','3')->where('durum','=','1')->paginate(9);
    	
    	if($hizmet_adi==null){
    		return view('hizmetler.hizmetler_arayuz')->with('hizmetler',$hizmet);
    	}else{

	    	$link = 'hizmetlerimiz/'.$hizmet_adi;
			$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('id')->get();

	    	return view('hizmetler.hizmet_icerik')->with('getLink',$link)
									  			  ->with('sayfaicerik',$sayfaicerik)
	    										  ->with('hizmetler',$hizmet);
	    }
    }

	
	public function linkGecmisiniBul($link){
		$guncel_link = LinkRecords::where('eski_link',$link)->orderBy('id','desc')->take(1)->pluck('yeni_link');
		return $guncel_link;
	}
	

    # DENENEBİLİR : $_SERVER['REQUEST_URI'] => DAHA MANTIKLI \Request::fullUrl() or \Request::url() or \Request::path()
	public function urunler($link1=null, $link2=null, $link3=null) # Max.:3 alt ürüne kadar eklenebilir.
    {	
    	$urunler_menu = "";

    	$language = explode("/", \Request::path());
    	if(App::getLocale() != 'tr'){ ### MENU İÇİN

    		if(App::getLocale() == 'ar'){ # Arapça linklerde GET parametreleri tersten alınıyor.
    			if(isset($language['2']))
    				$urunDil = $language['1'] . '/'. urldecode($language['2']); # ar/منتجات
    			else
    				$urunDil = $language['1']; # ar/منتجات

    		}else{
    			$urunDil = $language['0'] . '/'. $language['1']; # en/products
    		}
    		
    		$urunler_menu = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')->where('anasayfada_goster','=','1')
		                            ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
		                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->where('kategori_id','=','3')                           
		                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->paginate(9);


    	}else{ # For Turkish, without 'lang' slang.
    		$urunDil = $language['0'];  
    		$urunler_menu = SabitSayfalar::where('kategori_id','=','3')->where('durum','=','1')->where('ust_sayfa',null)->get();
    	}



    	if($link1==null && $link2==null && $link3==null){
	    		
	    	if(App::getLocale() == 'tr'){
	    		$urunler = SabitSayfalar::where('kategori_id','=','3')->where('durum','=','1')->where('ust_sayfa',null)->paginate(9);

	    	}else{

	            $urunler = SabitSayfalar::where('durum','=','1')->whereNull('ust_sayfa')->where('anasayfada_goster','=','1')
	                            ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
	                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->where('kategori_id','=','3')                           
	                            ->where('sabit_sayfalar_translate.dil','=',App::getLocale())->paginate(9);
	    	}


    		return view('urunler.urunler_arayuz')->with('urunler',$urunler)
    											 ->with('sayfalama','olsun')
	    									  	 ->with('urunler_menu',$urunler_menu);
    	}

    	else if($link1!=null && $link2==null && $link3==null){

    		$sayfaicerik = "";
    		$altsayfa = "";

			if(App::getLocale() == 'tr'){

				$link = $urunDil. "/" . $link1;
				$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('id')->get();
				
				if(isset($sayfaicerik[0])){
					$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['id'])->get();
				}else{
					$guncel_link = $this->linkGecmisiniBul($link);
					if(isset($guncel_link[0])&& $guncel_link[0] != null){
						return redirect($guncel_link[0]);
					}else{
						return view('errors.404');
					}
				}
				

			}else{

				if(App::getLocale() == 'ar'){
					$link = $language['0']  .'/'. $link1 . '/'. urldecode($language['2']);

				}else{
					$link = $urunDil.'/'.$link1;
				}

				$sayfaicerik = SabitSayfalarTranslate::select('sabit_sayfalar.durum','sabit_sayfalar_translate.*')->where('sabit_sayfalar.durum','=',1)
													 ->where('sabit_sayfalar_translate.sayfa_linki',urldecode($link))->where('dil',App::getLocale())
													 ->join('sabit_sayfalar','sabit_sayfalar.id','=','sabit_sayfalar_translate.sabit_sayfalar_id')->get();

				
							 
				if(isset($sayfaicerik[0])){
					$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['sabit_sayfalar_id'])
							 ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
							 ->where('dil',App::getLocale())->get();
				}else{
					$guncel_link = $this->linkGecmisiniBul($link);
					if(isset($guncel_link[0])&& $guncel_link[0] != null){
						return redirect($guncel_link[0]);
					}else{
						return view('errors.404');
					}
				}
			}

			if(isset($altsayfa[0])){ #alt sayfa varsa göster abi. 
				return view('urunler.urunler_arayuz')->with('urunler',$altsayfa)
											  		->with('sayfaicerik',$sayfaicerik)
    											 	->with('sayfalama','olmasin')
	    									  		->with('urunler_menu',$urunler_menu);
			}else{

	    	return view('urunler.urun_icerik')->with('getLink',$link)
											  ->with('sayfaicerik',$sayfaicerik)
	    									  ->with('urunler',$sayfaicerik)
	    									  ->with('urunler_menu',$urunler_menu);
			}
    	}

    	else if($link1!=null && $link2!=null && $link3==null){

    		$sayfaicerik = "";
    		$altsayfa = "";

			if(App::getLocale() == 'tr'){

				$link = $urunDil. "/" .$link1 . '/'. $link2;
				$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('id')->get();
				
				if(isset($sayfaicerik[0])){ 
					$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['id'])->get();
				}else{
					$guncel_link = $this->linkGecmisiniBul($link); 
					if(isset($guncel_link[0]) && $guncel_link[0] != null){
						return redirect($guncel_link[0]);
					}else{
						return view('errors.404');
					}
				}

			}else{

				if(App::getLocale() == 'ar'){
					$link = $language['0']  .'/'. $link1 . '/'. $link2 . '/'.urldecode($language['3']); 
					## $language['4'] bir önceki arapça link oluşturma işleminde  "3"tü. Bu değer, linkin en sonunda yer alan ifadeyi alıyor.

				}else{
					$link = $urunDil.'/'.$link1. '/'. $link2;
				}

				$sayfaicerik = SabitSayfalarTranslate::select('sabit_sayfalar.durum','sabit_sayfalar_translate.*')->where('sabit_sayfalar.durum','=',1)
													 ->where('sabit_sayfalar_translate.sayfa_linki',urldecode($link))->where('dil',App::getLocale())
													 ->join('sabit_sayfalar','sabit_sayfalar.id','=','sabit_sayfalar_translate.sabit_sayfalar_id')->get();
				
													 
				
			
				if(isset($sayfaicerik[0])){
					$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['sabit_sayfalar_id'])
							 ->join('sabit_sayfalar_translate','sabit_sayfalar_translate.sabit_sayfalar_id','=','sabit_sayfalar.id')
							 ->where('dil',App::getLocale())->get();
				}else{
					$guncel_link = $this->linkGecmisiniBul($link);
					if(isset($guncel_link[0])&& $guncel_link[0] != null){
						return redirect($guncel_link[0]);
					}else{
						return view('errors.404');
					}
				}
			
			}

			if(isset($altsayfa[0])){ #alt sayfa varsa göster abi.  
				return view('urunler.urunler_arayuz')->with('urunler',$altsayfa)
											  		->with('sayfaicerik',$sayfaicerik)
    											 	->with('sayfalama','olmasin')
	    									  		->with('urunler_menu',$urunler_menu);
			}else{

	    	return view('urunler.urun_icerik')->with('getLink',$link)
											  ->with('sayfaicerik',$sayfaicerik)
	    									  ->with('urunler',$sayfaicerik)
	    									  ->with('urunler_menu',$urunler_menu);
			}
		}
    }


	# DENENEBİLİR : $_SERVER['REQUEST_URI']

	public function urunlerEticaret($link1=null, $link2=null, $link3=null) # Max.:3 alt ürüne kadar eklenebilir.
    {
    	$urunler_menu = SabitSayfalar::select('sayfa_adi','sayfa_linki')->where('kategori_id','=','3')->where('durum','=','1')->where('ust_sayfa',null)->get();

    	if($link1==null && $link2==null && $link3==null){

    		// $urunler = SabitSayfalar::where('kategori_id','=','3')->where('durum','=','1')->where('ust_sayfa',null)->paginate(9); # Kategorileri getiriyor.
    		$urunler = SabitSayfalar::where('kategori_id','=','3')->where('durum','=','1')->where('ust_sayfa','!=','not null')
    															  ->join('urun_ozellikleri', 'urun_ozellikleri.urun_id','=','sabit_sayfalar.id')->where('urun_ozellikleri.aktiflik','=','1')->paginate(24);
    		
    		return view('urunler.urunler_arayuz')->with('urunler',$urunler)
    											  ->with('sayfalama','olsun')
	    									  	  ->with('urunler_menu',$urunler_menu);
    	
    	}

    	else if($link1!=null && $link2==null && $link3==null){

	    	$link = 'urunlerimiz/'.$link1; # >> $_SERVER['REQUEST_URI']
			$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('sabit_sayfalar.id')->get(); # Kahvaltılık bir ürün değil, kategori. JOIN yapılamaz.
			
			$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['id'])
															 ->join('urun_ozellikleri', 'urun_ozellikleri.urun_id','=','sabit_sayfalar.id')->where('urun_ozellikleri.aktiflik','=','1')->get();
			
			$gorseller = Resimler::where('aktiflik','=','1')->where('r_kategori_id',$sayfaicerik[0]['id'])->get();

			if(isset($altsayfa[0])){ #alt sayfa varsa göster abi.
				return view('urunler.urunler_arayuz')->with('urunler',$altsayfa)
    											 	  ->with('sayfalama','olmasin')
													  ->with('urunler_menu',$urunler_menu)
													  ->with('sayfaicerik',$sayfaicerik);
			}else{
				
	    	return view('urunler.urun_icerik')->with('getLink',$link)
											  	->with('sayfaicerik',$sayfaicerik)
	    									  	->with('gorseller',$gorseller)
	    									  	->with('urunler_menu',$urunler_menu)
	    									  	->with('sayfaicerik',$sayfaicerik);
			}
    	}

    	else if($link1!=null && $link2!=null && $link3==null){ ## Sayfa içeriği

	    	$link = 'urunlerimiz/'.$link1.'/'.$link2;
			$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->join('urun_ozellikleri', 'urun_ozellikleri.urun_id','=','sabit_sayfalar.id')->where('urun_ozellikleri.aktiflik','=','1')->get();
			
			$gorseller = Resimler::where('aktiflik','=','1')->where('r_kategori_id',$sayfaicerik[0]['id'])->get();

			$altsayfa = SabitSayfalar::where('durum','=','1')->where('ust_sayfa',$sayfaicerik[0]['urun_id'])
																 ->join('urun_ozellikleri', 'urun_ozellikleri.urun_id','=','sabit_sayfalar.id')->where('urun_ozellikleri.aktiflik','=','1')->get();

			if(isset($altsayfa[0])){ #alt sayfa varsa göster abi.
				return view('urunler.urunler_arayuz')->with('urunler',$altsayfa)
    											 	  ->with('sayfalama','olmasin')
	    									  		  ->with('gorseller',$gorseller)
	    									  		  ->with('urunler_menu',$urunler_menu);
			}else{  #içerik.
			
	    	return view('urunler.urun_icerik')->with('getLink',$link)
											  	  ->with('sayfaicerik',$sayfaicerik)
	    									  	  ->with('gorseller',$gorseller)
	    									  	  ->with('urunler_menu',$urunler_menu);
			}
    	
    	}else{

	    	$link = 'urunlerimiz/'.$link1.'/'.$link2.'/'.$link3;
			$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('sabit_sayfalar.id')
																->join('urun_ozellikleri', 'urun_ozellikleri.urun_id','=','sabit_sayfalar.id')->where('urun_ozellikleri.aktiflik','=','1')->get();

			$gorseller = Resimler::where('aktiflik','=','1')->where('r_kategori_id',$sayfaicerik[0]['id'])->get();

	    	return view('urunler.urun_icerik')->with('getLink',$link)
												  ->with('sayfaicerik',$sayfaicerik);
	    }
    }







    public function galeri($galeri_adi=null)
    {
 		$belge = SabitSayfalar::where('kategori_id','=','5')->where('durum','=','1')->get();
    	$resim = Resimler::paginate(12);
    	
    	if($galeri_adi==null){
    		return view('galeri.galeri_arayuz1')->with('galeri',$belge)
		    							 ->with('resimler',$resim);
    	}else{
		    $link = 'galeri/'.$galeri_adi;
			$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->orderby('id')->get();
    		
		    return view('galeri.galeri_icerik')->with('getLink',$link)
		    							->with('sayfaicerik',$sayfaicerik)
		    							->with('resimler',$resim)
		    							->with('galeri',$belge);
	    }
    }

    public function insanKaynaklari($ik_adi=null)
    {
    	$ik = SabitSayfalar::where('kategori_id','=','6')->where('durum','=','1')->paginate(9);

    	if($ik_adi == ''){
    		$link = 'insan-kaynaklari';
    	}else{
    		$link = 'insan-kaynaklari/'.$ik_adi;
    	}

		$sayfaicerik = SabitSayfalar::where('durum','=','1')->where('sayfa_linki',$link)->get();

    	return view('ik.ik_icerik')->with('getLink',$link)
										  ->with('sayfaicerik',$sayfaicerik)
    									  ->with('ik',$ik);
    }
	
	public function goruntule_desktop($id=null, $desktop=null)
	{
		$menu_kontrol = SabitSayfalar::where('sayfa_linki',$id)->get();
        $kategori_kontrol = SabitSayfaKategori::where('link',$id)->get();

        if(sizeof($menu_kontrol) == 0 && sizeof($kategori_kontrol) == 0)
		{
			return view('errors.404'); //Sayfa yoksa anasayfaya git.
		}

		else
		{
			$desktop = "";
			//AppServiceProvider'dan geliyor diğer bilgiler.
			return view('sabit_sayfa_icerik')->with('getLink', $id)
									  		 ->with('sayfaicerik',$sayfaicerik)
											 ->with('desktop', $desktop);
		}
	}
	

	public function arsiv()
	{	
		$blogSonIceriklerMenu = BlogIcerik::where('durum','=','1')->orderBy('id', 'desc')->take(20)->get();
		return view("arsiv")->with('arsiv', null)
							->with('blogSonIceriklerMenu',$blogSonIceriklerMenu)
							->with('arsivDurum', null);
	}

		
	public function arsivGoruntule(Request $req)
	{
		$range = $req->only("baslangic","bitis");
		$blogSonIceriklerMenu = BlogIcerik::where('durum','=','1')->orderBy('id', 'desc')->take(20)->get();
		
		if($range["baslangic"] <= $range["bitis"] && $range["baslangic"] != null && $range["bitis"] !=null)
		{
			$arsiv = BlogIcerik::where("durum","1")->where("kategori_id","1")->where("tarih",">=",$range["baslangic"])->where("tarih","<=",$range["bitis"])->get();
		
		
			return view("arsiv")->with('arsiv',$arsiv)
								->with('blogSonIceriklerMenu',$blogSonIceriklerMenu)
								->with('arsivDurum',"goster");
		}
		
		else
		{
			return view("arsiv")->with("arsiv",null)
							    ->with('blogSonIceriklerMenu',$blogSonIceriklerMenu)
								->with('arsivDurum',"hata");
		}
	}

	public function iletisimSayfasi(){

		$bilgiler = IletisimBilgileri::where('durum','1')->get();

        $konum = array();
        foreach ($bilgiler as $key => $value) {
          $koor = explode(',', $value->konum);
          $konum[$key] =  [$value->sube, $koor[0], $koor[1], $key];
        }

        //dd($konum);

        $xcrud = Xcrud::get_instance();
        $xcrud->table('iletisim_formu');
		
			if(App::getLocale() == 'tr')
				$xcrud->language('tr_formgenerator');
			else if(App::getLocale() == 'ar')
				$xcrud->language('ar_formgenerator');
			else
				$xcrud->language('en_formgenerator');
		
        $xcrud->theme('scercevesiz');
        
        $xcrud->label(array(
            'if_adi' => \Lang::get('translate.Ad Soyad'),
            'if_eposta' => \Lang::get('translate.Email'),
            'if_telefon' => \Lang::get('translate.Telefon'),
            'if_mesajiniz' => \Lang::get('translate.Mesajınız'),
            'if_konu' => \Lang::get('translate.Konu'),
            'if_dosya_adi' => \Lang::get('translate.Dosya')
         ));
		
        $xcrud->unset_list();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
		$xcrud->unset_title();
		
        $xcrud->change_type('if_kid','hide');
        $xcrud->change_type('if_tarih','hide');
        $xcrud->change_type('if_dosya_adi','hide');
        $xcrud->change_type('if_id','hide');
		
        $xcrud->no_editor('if_mesajiniz');
		
        #Zorulu alanlar:
        $xcrud->validation_required('if_adi');
        $xcrud->validation_required('if_eposta');
        $xcrud->validation_required('if_telefon');
        $xcrud->validation_required('if_mesajiniz');
        $xcrud->validation_required('if_konu');

        $xcrud->after_insert('iletisimformukayit');

		return view('iletisim.iletisim_arayuz1')->with('xcrud',$xcrud)
                                                ->with('koordinatlar',$konum)
        									    ->with('bilgiler',$bilgiler);
	}

}
