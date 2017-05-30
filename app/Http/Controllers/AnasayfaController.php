<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Slider;
use App\BlogMenu;  //Şuana kadar hiç kullanılmadı.
use App\BlogIcerik;
use App\SabitSayfalar;
use App\EBulten;
use App;
use Session;
use Xcrud;
use Redirect;
include('xcrud/xcrud/xcrud.php');

class AnasayfaController extends Controller
{

	public function anasayfa($dil=null)
	{
		$desktop = "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>";
		
		$slider = Slider::where('durum','=','1')->orderBy('siralama')->get();
    	return view('anasayfa')->with('sliders',$slider);
	}
	
	public function ara(Request $req)
	{	
		$ara = $req->only("keywordstosearch");

		if($ara['keywordstosearch'] != null){
			Session::flash('aramaterimi',$ara['keywordstosearch']);
			
			/* $sabitsayfa_aramasi = SabitSayfalar::where('sayfa_icerik','LIKE', '%'.$ara['keywordstosearch'].'%')
												->whereNotNull('ust_sayfa')
												->orWhere('sayfa_adi','LIKE', '%'.$ara['keywordstosearch'].'%')
												->whereNotNull('ust_sayfa')
												->having('durum','=','1')->get(); */

			$sabitsayfa_aramasi = SabitSayfalar::where('sayfa_adi','LIKE', '%'.$ara['keywordstosearch'].'%')
												->whereNotNull('ust_sayfa')
												->having('durum','=','1')->get();

			$urunler_menu = SabitSayfalar::select('sayfa_adi','sayfa_linki')
										 ->where('kategori_id','=','3')
										 ->where('durum','=','1')->where('ust_sayfa',null)->get();

			
			
			return view('urunler.eticaret_arayuz')->with('urunler',$sabitsayfa_aramasi)
												  ->with('urunler_menu',$urunler_menu)
												  ->with('aramakelimesi',$ara['keywordstosearch'])
												  ->with('sayfalama',"");
		}else{
			return redirect('urunlerimiz');
		}
	}

	public function desktop()
	{
		Session::set('desktop','masaustu_surum');

		$slider = Slider::where('durum','=','1')->where('siralama','=','1')->get();
    	return view('anasayfa')->with('sliders',$slider);
	}
	
	public function ebulten(Request $req)
	{
		$data = $req->only('eposta');
		$kontrol = EBulten::where('eposta',$data['eposta'])->count();

		if ($kontrol==0) {
			$bultenkayit = new EBulten();
			$bultenkayit->fill($data);
			$bultenkayit->save();

		echo '<script type="text/javascript">
				alert("'. $data['eposta'] .' adlı mail adresiniz başarıyla kaydedildi.");
				window.history.go(-1);
			 </script>';

		}else{
		echo '<script type="text/javascript">
				alert("'. $data['eposta'] .' adlı mail adresi ile daha önceden kayıt yapılmış.");
				window.history.go(-1);
			 </script>';
		}
	}	
	
}
