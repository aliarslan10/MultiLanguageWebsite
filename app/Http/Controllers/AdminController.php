<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Admin;
use Session;
use Xcrud;
use Lang;
use App\TopluMailSablonu;
use App\KullaniciBilgileri;

include('xcrud/xcrud/xcrud.php');

class AdminController extends Controller
{
    public function anasayfaAyarlari()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('site_ayarlari');
        $xcrud->table_name('Edit Website Information');

        $xcrud->label(array(
            'title' => Lang::get('admintranslate.Title'),
            'enust_bilgi' => Lang::get('admintranslate.Alt Açıklama Yazısı 1'),
            'footer1'=>Lang::get('admintranslate.Alt Açıklama Yazısı 2'),
            'footer2'=>Lang::get('admintranslate.Alt Açıklama Yazısı 3'),
            'facebook'=>Lang::get('admintranslate.Facebook Sayfanızın Linki'),
            'twitter'=>Lang::get('admintranslate.Twitter Sayfanızın Linki'),
            'youtube'=>Lang::get('admintranslate.Yotube Sayfanızın Linki'),
            'instagram'=>Lang::get('admintranslate.İnstagram Sayfanızın Linki'),
            'google_plus'=>Lang::get('admintranslate.Google Plus Sayfanızın Linki'),
            'description'=>Lang::get('admintranslate.Description')
        ));

        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();
        $xcrud->unset_add();

        $xcrud->hide_button('save_return');
        $xcrud->hide_button('return');

        $xcrud->change_type('sayfa_id','hidden');
		$xcrud->change_type('footer1','hidden');
		$xcrud->change_type('footer2','hidden');
       /*  $xcrud->change_type('facebook','hidden');
        $xcrud->change_type('twitter','hidden');
        $xcrud->change_type('youtube','hidden');*/ 
        $xcrud->change_type('enust_bilgi','hidden'); 
        $xcrud->change_type('durum,youtube','hidden');
        $xcrud->no_editor('footer1, footer2,enust_bilgi');

        return view('admin.site_ayar')->with('xcrud',$xcrud);
    }

   public function menuler(){

        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfa_kategori');
        $xcrud->where('developer_set','1');
        $xcrud->table_name('Menüler');
        $xcrud->order_by('sira');

        $xcrud->label(array(
            'kategori' => Lang::get('admintranslate.Menü Adı'),
            'icerik'=>Lang::get('admintranslate.İçerik'),
            'updated_at'=>Lang::get('admintranslate.Aktif'),
            'created_at'=>Lang::get('admintranslate.Kategori'),
            'durum' => Lang::get('admintranslate.Ana Menüde Gösterilsin'),
            'link' => Lang::get('admintranslate.Link'),
            'description' => Lang::get('admintranslate.Kısa Açıklama'),
            'alt_menu' => Lang::get('admintranslate.Tıklanınca Alt Menüler Gösterilsin')
        ));

        $xcrud->before_insert('kategoriSeflinkEkleTR'); 
        //$xcrud->before_update('kategoriSeflinkEkleTR'); 
        
        $xcrud->columns('kategori, durum, alt_menu,menudeki_yeri'); 

        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();
        $xcrud->unset_add();
        $xcrud->unset_remove();
        
        $xcrud->change_type('id','hide');
        $xcrud->change_type('icerik','hide');
        $xcrud->change_type('updated_at','hide');
        $xcrud->change_type('created_at','hide');
        $xcrud->change_type('developer_set','hide');
        $xcrud->change_type('description','hide');
        $xcrud->change_type('link','hide');

        //$xcrud->field_callback('link','nice_input');

        $xcrud->button('/{link}','Menüye Git','','',array('target'=>'_blank'));

        return view('admin.goster')->with('xcrud',$xcrud);
    }

    public function sliderYonetimi()
    {
        $slider = Xcrud::get_instance();
        $slider->table('sliders');
        $slider->table_name('Edit Slider Image On Homepage');
        $slider->order_by('siralama');
        $slider->where('tur','Manset');

        $slider->label(array(
            'slider_adi' => Lang::get('admintranslate.Adı'),
            'slider_icerik'=>Lang::get('admintranslate.İçerik'),
            'slider_resim_url' => Lang::get('admintranslate.Görsel'),
            'durum'=>Lang::get('admintranslate.Aktif'),
            'link'=>Lang::get('Link (If you want you can add)')
        ));

        $slider->change_type('slider_resim_url', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\slider'));
        $slider->columns('slider_resim_url, slider_adi, durum');
        $slider->change_type('slider_icerik, tur','hide');

        $slider->unset_print();
        $slider->unset_csv();
        $slider->unset_remove();

        ################################################# MANŞET ALANI YÖNETİMİ #################################################
        $banner = Xcrud::get_instance();
        $banner->table('sliders');
        $banner->table_name('Banner Yönetimi');
        $banner->order_by('siralama');
        $banner->where('tur','Banner');

        $banner->label(array(
            'slider_adi' => Lang::get('Adı'),
            'slider_icerik'=>Lang::get('İçerik'),
            'slider_resim_url' => Lang::get('Görsel'),
            'durum'=>Lang::get('Aktif')
        ));

        $banner->columns('slider_resim_url, slider_adi, slider_icerik, tur, durum');
        $banner->change_type('slider_icerik', 'hidden');

        $banner->change_type('slider_resim_url', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\banner'));
        $banner->validation_required('slider_resim_url, link');

        $banner->unset_print();
        $banner->unset_csv();
        $banner->unset_remove();
          
        return view('admin.goster')->with('xcrud', $slider);
    }
    
    public function kurumsal()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfalar');
        $xcrud->where('kategori_id =','2');
        $xcrud->table_name('Edit Content About Company');

       $xcrud->label(array(
            'sayfa_adi' => Lang::get('admintranslate.Sayfa Adı / Başlık'),
            'durum'=>Lang::get('admintranslate.Aktiflik'),
            'kisa_aciklama'=> Lang::get('admintranslate.Kısa Açıklama (max:160 krktr)'),
            'sayfa_icerik'=>Lang::get('admintranslate.Sayfa İçeriği'),
            'sayfa_linki' => Lang::get('admintranslate.Sayfa Linki'),
            'resim_linki' => Lang::get('admintranslate.Ana Görsel'),
            'icerik' => Lang::get('admintranslate.İçerik'),
            'kategori_id'=> Lang::get('admintranslate.Kategori Adı')
        ));
        
        $xcrud->before_insert('sabitSayfaSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.
        $xcrud->before_update('sabitSayfaSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.

        $xcrud->columns('sayfa_adi,sayfa_icerik, durum, sayfa_linki'); 
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();

        $xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\kurumsal'));

        //bu tablodaki sutun, diğer tablo adı, diğer tablo idsi, çekilecek kolon.
        //$xcrud->relation('kategori_id','sabit_sayfa_kategori','id','kategori');

        $xcrud->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');

        $xcrud->field_callback('sayfa_linki','nice_input');

        $xcrud->pass_var('kategori_id', '2');
        $xcrud->change_type('kategori_id,sayfa_id,ust_sayfa,resimler_id,kisa_aciklama,kategori_id,icon,anasayfada_goster,menudeki_yeri','hide');


        $xcrud->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));

        ######################################################################################
        #################################### DİĞER DİLLER ####################################
        ######################################################################################
        $xcrud->default_tab('Türkçe');
        $translate = $xcrud->nested_table(Lang::get('admintranslate.Translate (Diğer Diller)'),'id','sabit_sayfalar_translate','sabit_sayfalar_id');
		
		$translate->label(array(
            'sayfa_adi' => Lang::get('admintranslate.Sayfa Adı'),
            'sayfa_icerik'=>Lang::get('admintranslate.Sayfa İçerik'),
            'dil'=>Lang::get('admintranslate.Dil'),
            'sayfa_linki'=>Lang::get('admintranslate.Sayfa Linki'),
        ));		
		
		
        $translate->columns('sayfa_adi,sayfa_icerik,dil');
        $translate->fields('dil, sayfa_adi, sayfa_icerik, resim_linki, sayfa_linki');
        $translate->readonly('dil');
        $translate->field_callback('sayfa_linki','nice_input');
        $translate->default_tab(Lang::get('admintranslate.Translate (Diğer Diller)'));
        $translate->table_name(Lang::get('admintranslate.Translate (Diğer Diller)'));
        $translate->unset_add();
        $translate->unset_remove();
        $translate->unset_csv();
        $translate->unset_print();
        $translate->before_update('kurumsalSeflinkTranslate');
        $translate->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\products'));
        ######################################################################################
        ######################################################################################
		
        return view('admin.goster')->with('xcrud', $xcrud);
    }

    public function insanKaynaklari()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfalar');
        $xcrud->where('kategori_id =','6');
        $xcrud->table_name('İnsan Kaynakları');

       $xcrud->label(array(
            'sayfa_adi' => Lang::get('Sayfa Adı / Başlık'),
            'durum'=>Lang::get('Aktiflik'),
            'kisa_aciklama'=> Lang::get('Kısa Açıklama (max:160 krktr)'),
            'sayfa_icerik'=>Lang::get('Sayfa İçeriği'),
            'sayfa_linki' => Lang::get('Sayfa Linki'),
            'resim_linki' => Lang::get('Ana Görsel'),
            'icerik' => Lang::get('İçerik'),
            'kategori_id'=> Lang::get('Kategori Adı')
        ));
        
        $xcrud->before_insert('ikSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'ikSeflinkEkleTR' adlı fonksiyon.
        $xcrud->before_update('ikSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'ikSeflinkEkleTR' adlı fonksiyon.

        $xcrud->columns('sayfa_adi, kategori_id, kisa_aciklama, resim_linki, sayfa_icerik, durum, sayfa_linki'); 
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();

        $xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\ik'));

        //bu tablodaki sutun, diğer tablo adı, diğer tablo idsi, çekilecek kolon.
        //$xcrud->relation('kategori_id','sabit_sayfa_kategori','id','kategori');

        $xcrud->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');

        $xcrud->field_callback('sayfa_linki','nice_input');

        $xcrud->pass_var('kategori_id', '6');
        $xcrud->change_type('sayfa_id','hidden');
        $xcrud->change_type('kategori_id','hidden');
        $xcrud->change_type('kisa_aciklama','hidden');
        $xcrud->change_type('resimler_id','hidden');

        $xcrud->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));
    
        return view('admin.goster')->with('xcrud', $xcrud);
    }


    public function galeri()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfalar');
        $xcrud->where('kategori_id =','5');
        $xcrud->table_name('Galeri Albümleri');

        $xcrud->label(array(
            'sayfa_adi' => Lang::get('Albüm Adı'),
            'durum'=>Lang::get('Menüde Göster'),
            'sayfa_icerik'=>Lang::get('Sayfa İçeriği'),
            'sayfa_linki' => Lang::get('Sayfa Linki'),
            'resim_linki' => Lang::get('Albüm Kapak Resmi'),
            'icerik' => Lang::get('İçerik'),
            'kategori_id'=> Lang::get('ategori Adı')
        ));
        
        $xcrud->before_insert('galeriSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.
        $xcrud->before_update('galeriSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.

        $xcrud->columns('sayfa_adi, kategori_id, kisa_aciklama, resim_linki, sayfa_icerik, durum'); 
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();

        $xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\galeri'));

        ################LİNK:
        $xcrud->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');

        $xcrud->field_callback('sayfa_linki','nice_input');

        ####################################
        //bu tablodaki sutun, diğer tablo adı, diğer tablo idsi, çekilecek kolon.
        //$xcrud->relation('kategori_id','sabit_sayfa_kategori','id','kategori');
        $xcrud->pass_var('kategori_id', '5');
        $xcrud->change_type('sayfa_id','hidden');
        $xcrud->change_type('kategori_id','hidden');
        $xcrud->change_type('resimler_id','hidden');
        $xcrud->change_type('kisa_aciklama','hidden');
        $xcrud->change_type('sayfa_icerik','hidden');
    
        $xcrud->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));

        //sabit sayfalar'dan : id ;;;;;; resimler tablosundan: r_kategori_id
        $resimler = $xcrud->nested_table('Galeri Resimleri', 'id', 'resimler', 'r_kategori_id');

        $xcrud->default_tab('Galeri Adı');
        
        $resimler->label(array(
            'r_resim' => Lang::get('Fotoğraf'),
            'r_aciklama' => Lang::get('Fotoğraf Adı'),
        ));
        
        $resimler->change_type('r_id','hidden');
        $resimler->change_type('r_kategori_id','hidden');


        $resimler->unset_title();
        $resimler->unset_remove();
        $resimler->unset_csv();
        $resimler->unset_search();
        $resimler->unset_print();
        $resimler->start_minimized();
        $resimler->unset_print();

        $resimler->change_type('r_resim', 'image','', array('path'=>'..\..\img\galeri', 'not_rename'=>true));

        //$xcrud->fields('sayfa_adi,icerik,sayfa_icerik,durum,sayfa_linki,resim_linki,description', 'Kategori Adı');
        //$resimler->fields('r_resim', 'Resimler');
       


        return view('admin.goster')->with('xcrud', $xcrud);   
    }

    public function hizmetler()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfalar');
        $xcrud->table_name('Hizmetler');
        $xcrud->where('kategori_id = ', 3);

        $xcrud->label(array(
            'sayfa_adi' => Lang::get('Ürün Adı'),
            'sayfa_icerik'=>Lang::get('Açıklama'),
            'durum'=>Lang::get('Aktif'),
            'sayfa_linki' => Lang::get('Sayfa Linki')
        ));
                
        #Zorulu alanlar:
        #$xcrud->validation_required('baslik');
        #$xcrud->validation_required('icerik');
        #$xcrud->validation_required('tarih');
        #$xcrud->validation_required('resim_linki');
        #---------------------------------------------#

        $xcrud->before_insert('hizmetlerSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.
        $xcrud->before_update('hizmetlerSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'hizmetSeflinkEkleTR' adlı fonksiyon.

        $xcrud->columns('sayfa_adi, sayfa_icerik, resim_linki, durum');

        $xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\hizmetler'));

        $xcrud->change_type('kategori_id','hide');
        $xcrud->change_type('kisa_aciklama','hide');
        $xcrud->change_type('icon','hide');
        $xcrud->change_type('resimler_id','hide');

        $xcrud->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');

        $xcrud->column_pattern('sayfa_linki','<div class="input-group">
                                <span style="background-color:#e5e5e5;" class="input-group-addon" id="basic-addon3">https://www.bilimis.com.tr/</span>
                                <input type="text" class="form-control" value="{sayfa_linki}" id="basic-url" aria-describedby="basic-addon3">
                                </div>');

        $xcrud->field_callback('sayfa_linki','nice_input');

        $xcrud->pass_var('kategori_id',3);
    
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();
        
        $xcrud->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));
        $xcrud->after_insert('dilSecenekleriniOtomatikOlustur');

        return view('admin.goster')->with('xcrud', $xcrud);
    }

    public function urunKategorileri()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('sabit_sayfalar');
        $xcrud->table_name(Lang::get('admintranslate.Ürün Kategorileri <small> - Buradan siteye yeni ürün kategorisi eklenebilir.</small>'));
        $xcrud->where('kategori_id = ', 3)->where('ust_sayfa IS NULL');

        $xcrud->label(array(
            'sayfa_adi' => Lang::get('admintranslate.Sayfa Adı'),
            'sayfa_icerik'=>Lang::get('admintranslate.Açıklama'),
            'durum'=>Lang::get('admintranslate.Aktif'),
            'sayfa_linki' => Lang::get('admintranslate.Sayfa Linki'),
            'resim_linki' => Lang::get('admintranslate.Resim'),
            'description' => Lang::get('admintranslate.Arama Motoru Açıklaması'),
            'anasayfada_goster' => Lang::get('admintranslate.Anasayfada Göster')
        ));
                
        #Zorulu alanlar:
        #$xcrud->validation_required('baslik');
        #$xcrud->validation_required('icerik');
        #$xcrud->validation_required('tarih');
        #$xcrud->validation_required('resim_linki');
        #---------------------------------------------#

        $xcrud->column_cut(250,'description');
        $xcrud->column_width('description','55%');

        $xcrud->before_insert('urunlerSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'urunlerSeflinkEkleTR' adlı fonksiyon.
        $xcrud->before_update('urunlerSeflinkEkleTR'); //xcrud dosyasındaki functions.php sayfasındaki 'urunlerSeflinkEkleTR' adlı fonksiyon.

        $xcrud->columns('resim_linki, sayfa_adi, anasayfada_goster, durum');

        //$xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\urunler'));

        $xcrud->change_type('kategori_id,kisa_aciklama,icon,resimler_id,ust_sayfa,icon, menudeki_yeri','hide');

        $xcrud->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');

        $xcrud->field_tooltip('description', 'Bu kısma yazacağınız içerik arama motorlarında kullanıcıya gösterilecektir.');

        $xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\urunler'));

         $xcrud->field_callback('sayfa_linki','nice_input');

        $xcrud->pass_var('kategori_id',3);
    
        $xcrud->unset_print();
        $xcrud->unset_csv();
        //$xcrud->unset_add();
        $xcrud->unset_remove();
        $xcrud->unset_pagination();
        $xcrud->unset_limitlist();
        $xcrud->unset_search();
        $xcrud->limit('all');

        $xcrud->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));
		
		## Fonksiyonel İşlemler
        $xcrud->before_insert('urunlerSeflinkEkleTR');
        $xcrud->before_update('urunlerSeflinkGuncelleTR');
        $xcrud->after_insert('dilSecenekleriniOtomatikOlustur'); # İlk LinkRecords kaydı da burada oluyor.
        $xcrud->after_update('changePicture'); //anadil için foto eklenince translate tablosuna da eklensin.
        
        ######################################################################################
        #################################### DİĞER DİLLER ####################################
        ######################################################################################
        $xcrud->default_tab(Lang::get('admintranslate.Türkçe'));
        $translate = $xcrud->nested_table(Lang::get('admintranslate.Translate (Diğer Diller)'),'id','sabit_sayfalar_translate','sabit_sayfalar_id');
		
		$translate->label(array(
            'sayfa_adi' => Lang::get('admintranslate.Sayfa Adı'),
            'sayfa_icerik'=>Lang::get('admintranslate.Sayfa İçerik'),
            'dil'=>Lang::get('admintranslate.Dil'),
            'sayfa_linki'=>Lang::get('admintranslate.Sayfa Linki'),
        ));
		
        $translate->columns('sayfa_adi,sayfa_icerik,dil');
        $translate->fields('dil, sayfa_adi, sayfa_icerik, sayfa_linki');
        $translate->readonly('dil');
        $translate->field_callback('sayfa_linki','nice_input');
        $translate->default_tab(Lang::get('admintranslate.Translate (Diğer Diller)'));
        $translate->table_name(Lang::get('admintranslate.Translate (Diğer Diller)'));
        $translate->unset_add();
        $translate->unset_remove();
        $translate->before_update('urunlerSeflinkTranslate');
        //$translate->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\products'));
        ######################################################################################
        ######################################################################################

        return view('admin.goster')->with('xcrud', $xcrud);
    }


    public function urunler()
    {
        ########################## ALT ÜRÜN ########################################
        $xcrud_alt_urun = Xcrud::get_instance();
        $xcrud_alt_urun->table('sabit_sayfalar');
        $xcrud_alt_urun->table_name('Sitede Yer Alan Tüm Ürünler - <small>Bu alandan ürün bilgilerini düzenleyebilir veya siteye yeni ürün ekleyebilirsiniz.</small>');
        $xcrud_alt_urun->where('kategori_id = ', 3)->where('ust_sayfa IS NOT NULL');

        $xcrud_alt_urun->label(array(
            'sayfa_adi' => Lang::get('admintranslate.Ürün Adı'),
            'sayfa_icerik'=>Lang::get('admintranslate.Açıklama'),
            'durum'=>Lang::get('admintranslate.Aktif'),
            'sayfa_linki' => Lang::get('admintranslate.Sayfa Linki'),
            'anasayfada_goster' => Lang::get('admintranslate.Taviye Edilen Ürün'),
            'resim_linki' => Lang::get('admintranslate.Resim'),
            'ust_sayfa' => Lang::get('admintranslate.Ürün Kategorisi')
        ));
        
        #Zorulu alanlar:
        #$xcrud->validation_required('baslik');
        #$xcrud->validation_required('icerik');
        #$xcrud->validation_required('tarih');
        #$xcrud->validation_required('resim_linki');
        #---------------------------------------------#


        $xcrud_alt_urun->columns('resim_linki, sayfa_adi, ust_sayfa, sayfa_icerik, durum');

        $xcrud_alt_urun->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\urunler'));

        $xcrud_alt_urun->change_type('anasayfada_goster,kategori_id,icon,resimler_id,menudeki_yeri,kisa_aciklama','hide');
        $xcrud_alt_urun->no_editor('kisa_aciklama');

        $xcrud_alt_urun->field_tooltip('sayfa_linki', 'Bu kısım sayfa başlığına göre otomatik olarak oluşturulur.
        Link, Google\'da yer edindikten sonra ürün başlığının değiştirilmesi tavsiye edilmez.');      

        $xcrud_alt_urun->field_tooltip('anasayfada_goster', 'Tik işareti atılması durumunda bu ürün, site anasayfasındaki tavsiye edilen ürünler arasında görüntülenir.');

        $xcrud_alt_urun->field_callback('sayfa_linki','nice_input');

        $xcrud_alt_urun->pass_var('kategori_id',3);
        $xcrud_alt_urun->pass_var('menudeki_yeri','');

        $xcrud_alt_urun->relation('ust_sayfa','sabit_sayfalar','id','sayfa_adi', 'sabit_sayfalar.kategori_id=3 AND sabit_sayfalar.ust_sayfa IS NULL');
    
        $xcrud_alt_urun->field_tooltip('description', 'Bu kısma yazacağınız içerik arama motorlarında kullanıcıya gösterilecektir.');

        $xcrud_alt_urun->unset_print();
        $xcrud_alt_urun->unset_csv();
        $xcrud_alt_urun->unset_remove();
        $xcrud_alt_urun->limit(25);

        $xcrud_alt_urun->validation_required('ust_sayfa');

        # eğer her içeriğin ayrı bir menüde olması istenirse.
        //$xcrud->relation('kategori_id','alt_menu','id','menu_adi','','','','',array('primary_key'=>'id','parent_key'=>'menu_id'));
        
        $xcrud_alt_urun->button('/{sayfa_linki}','İçeriğe Git','','',array('target'=>'_blank'));

        $xcrud_alt_urun->default_tab('Ürün Bilgileri');

        #####################################################################################################################
        $resimler = $xcrud_alt_urun->nested_table('Ürün Resimleri', 'id', 'resimler', 'r_kategori_id');

        $resimler->label(array(
            'r_resim' => 'Fotoğraf',
            'r_aciklama' => 'Fotoğraf Adı',
        ));
        
        $resimler->change_type('r_id','hidden');
        $resimler->change_type('r_kategori_id','hidden');

        $resimler->unset_title();
        $resimler->unset_remove();
        $resimler->unset_csv();
        $resimler->unset_search();
        $resimler->unset_print();
        $resimler->start_minimized();
        $resimler->unset_print();

        $resimler->change_type('r_resim', 'image','', array('path'=>'..\..\img\urunler\urun', 'not_rename'=>true));

        #####################################################################################################################

        ## Fonksiyonel İşlemler
        $xcrud_alt_urun->before_insert('ALTurunlerSeflinkEkleTR');
        $xcrud_alt_urun->before_update('ALTurunlerSeflinkGuncelleTR');
        // $xcrud_alt_urun->after_insert('getUrunId'); E-Ticaret siteleri için.
        $xcrud_alt_urun->after_insert('dilSecenekleriniOtomatikOlustur');
        $xcrud_alt_urun->after_update('changePicture'); //anadil için foto eklenince translate tablosuna da eklensin.

        ######################################################################################
        #################################### DİĞER DİLLER ####################################
        ######################################################################################
        $xcrud_alt_urun->default_tab('Türkçe');
        $translate = $xcrud_alt_urun->nested_table('Translate','id','sabit_sayfalar_translate','sabit_sayfalar_id');
        $translate->columns('sayfa_adi,sayfa_icerik,dil');
        $translate->fields('dil, sayfa_adi, sayfa_icerik, sayfa_linki');
        $translate->readonly('dil');
        $translate->field_callback('sayfa_linki','nice_input');
        $translate->default_tab('Translate');
        $translate->table_name('Translate');
        $translate->unset_add();
        $translate->unset_remove();
        $translate->before_update('altSayfalarTranslateSeflinkEkle');
        //$translate->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\products'));
        ######################################################################################
        ######################################################################################

        return view('admin.goster')->with('xcrud', $xcrud_alt_urun);
    }

    public function firsatUrunleri()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('urun_ozellikleri');
        $xcrud->table_name('Fırsat Ürünleri Listesi - <small>Bu alandan sadece fırsat ürünlerini görebilir ve fiyatları güncelleyebilirsiniz. Ürünlerler ilgili daha fazla detay 
        güncellemek için <a href="/admin/urun-yonetimi"> buraya tıklayarak </a> ürünler listesine gidebilirsiniz.</small>');
        $xcrud->where('firsat_urunu = ', 1);

        $xcrud->change_type('urun_id, marka, aktiflik','hidden');
        $xcrud->field_tooltip('firsat_urunu','Bu tiki kaldırıp, kaydet yaptığınız takdirde ürün, fırsat ürünleri listesinden kalkar.');
    
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_add();
        $xcrud->unset_remove();
        $xcrud->unset_pagination();
        $xcrud->unset_limitlist();
        $xcrud->unset_search();
        $xcrud->limit('all');

        # eğer her içeriğin ayrı bir menüde olması istenirse.
        //$xcrud->relation('kategori_id','alt_menu','id','menu_adi','','','','',array('primary_key'=>'id','parent_key'=>'menu_id'));

        return view('admin.goster')->with('xcrud', $xcrud);

    }

	public function blogICerik()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('blog_icerik');
        $xcrud->table_name('Haber İçerikleri');
        $xcrud->order_by('id','desc');

        $xcrud->label(array(
            'baslik' => 'Başlık',
            'icerik'=>'İçerik',
            'durum'=>'Aktif',
            'kategori_id'=>'Kategori',
            'sayfa_linki' => 'Sayfa Linki',
            'resim_linki' => 'Ana Görsel',
			'tarih' => 'Tarih'
        ));
				
		#Zorulu alanlar:
		$xcrud->validation_required('baslik');
		$xcrud->validation_required('icerik');
		$xcrud->validation_required('tarih');
        $xcrud->validation_required('resim_linki');
		$xcrud->validation_required('kategori_id');
		#---------------------------------------------#

        $xcrud->before_insert('seflinkEkle'); //xcrud dosyasındaki functions.php sayfasındaki 'seflinkEkle' adlı fonksiyon.
        $xcrud->before_update('seflinkGuncelle'); //xcrud dosyasındaki function.php sayfasındaki 'seflinkGuncelle' adlı fonksiyon.
		
        $xcrud->columns('baslik, icerik, tarih, resim_linki, kategori_id, durum'); 
        $xcrud->change_type('sayfa_linki','hide');
		
		$xcrud->change_type('resim_linki', 'image', '', array('not_rename'=>true, 'path'=>'..\..\img\blog'));
			
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();

        //$xcrud->relation('kategori_id','blog_menu','id','menu_adi','','','','',array('primary_key'=>'id','parent_key'=>'menu_id'));
        //üstteki ile alttaki aynı şey.
        $xcrud->relation('kategori_id','blog_menu','id','menu_adi');
        
        return view('admin.goster')->with('xcrud', $xcrud);
    }

    public function blogKategori()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('blog_menu');
        $xcrud->table_name('Haber Kategorileri');

        $xcrud->label(array(
            'menu_adi' => 'Kategori',
            'kategori_link'=>'Kategori Linki',
            'durum'=>'Aktif'
        ));
                
        #Zorulu alanlar:
        $xcrud->validation_required('baslik');
        $xcrud->validation_required('icerik');
        $xcrud->validation_required('tarih');
        $xcrud->validation_required('resim_linki');
        #---------------------------------------------#

        $xcrud->before_insert('seflinkEkle'); //xcrud dosyasındaki functions.php sayfasındaki 'seflinkEkle' adlı fonksiyon.
        $xcrud->before_update('seflinkGuncelle'); //xcrud dosyasındaki function.php sayfasındaki 'seflinkGuncelle' adlı fonksiyon.
        
        $xcrud->columns('menu_adi, kategori_link, durum'); 
        $xcrud->change_type('menu_id','hide');

        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_remove();

        return view('admin.goster')->with('xcrud', $xcrud);
    }
	
    public function topluMailIletisi()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('toplu_mail');

        $xcrud->label(array(
                'konu' => 'Mail Konusu',
                'mesaj' => 'Mesajınız'
        ));

        $xcrud->columns('konu, mesaj');
        $xcrud->unset_list(); //geri dönmesin
        $xcrud->unset_add();  // yeni kayıt eklemesin
        $xcrud->change_type('mesaj', 'textarea');

        $xcrud->after_update('guncelleme_donusu'); //functions.php sitesindeki guncelleme_donusu() adlı fonk.dan geliyor alert uyarısı.

        return view('admin.toplu-mail-gonderimi')->with('xcrud',$xcrud);
    }

    public function iletisimBilgileri()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('iletisim_bilgileri');
        $xcrud->table_name(Lang::get('admintranslate.İletişim Bilgileri'));

        $xcrud->label(array(
                'sube' => Lang::get('admintranslate.Şube / Kurum Adı'),
                'telefon1' => Lang::get('admintranslate.Telefon Numarası'),
                'telefon2' => Lang::get('admintranslate.Alternatif Telefon Numarası'),
                'fax' => Lang::get('admintranslate.Fax'),
                'mail1' => Lang::get('admintranslate.Mail Adresi'),
                'mail2' => Lang::get('admintranslate.Alternatif Mail Adresi'),
                'adres' => Lang::get('admintranslate.Adres'),
                'konum' => Lang::get('admintranslate.Konum'),
                'durum' => Lang::get('admintranslate.Durum')
        ));

        $xcrud->columns('sube, telefon1, telefon2, mail1, mail2'); 

        $xcrud->change_type('id','hide');
        $xcrud->no_editor('adres');
        $xcrud->change_type('konum','point','',array('text'=>'Buradasınız'));
        
        $xcrud->unset_csv();
        $xcrud->unset_print();
        $xcrud->unset_search();
        $xcrud->unset_view();
        $xcrud->unset_remove();

        return view('admin.goster')->with('xcrud',$xcrud);
    }



    public function siparisler()
    {
        $xcrud = Xcrud::get_instance();
        $xcrud->table('siparis_detaylari');
        $xcrud->table_name('Siparişler');
        $xcrud->order_by('id','DESC');

        $xcrud->label(array(
            'created_at' => 'Sipariş Tarihi',
            'sifre'=>'Şifre',
            'toplam_odenen'=>'Ödenen Tutar',
            'ad_soyad' => 'Ad Soyad'
        ));
        
        $xcrud->columns('ad_soyad,mail_adresi, toplam_odenen, created_at, siparis_kodu, siparis_durumu');
        $xcrud->change_type('tckimlik,kullanici_id,updated_at,','hidden');
		
		/*
		$orderCode = '{id} * 472';

        $xcrud->subselect('Sipariş Kodu',$orderCode);
        $xcrud->change_type('Sipariş Kodu','price','3', array('prefix'=>'HTYCRS','separator'=>'', 'decimals'=>'0')); */
		
		//$xcrud->after_update('siparisOnayi'); # SERVER SIDE çalıştığı için SESSION işlemez. AJAX POST da olmaz o zaman da BASH failede sorunu.
		// laravel içinden post alınmıyor, TOKEN istiyor. 
		
		$xcrud->readonly('ad_soyad,mail_adresi, odeme_turu, toplam_odenen, created_at, Sipariş Kodu, ulke, sehir, adres, cep_telefonu, siparis_kodu');

        $xcrud->unset_add();
        $xcrud->unset_pagination();
        $xcrud->unset_remove();
        //$xcrud->unset_edit();
        $xcrud->unset_view();
        $xcrud->unset_search();

        $xcrud->default_tab('Sipariş Detayları');
        $siparisler = $xcrud->nested_table('Siparişler','id','siparisler','siparis_detay_id');
        $siparisler->columns('urun_adi, adet, birim_fiyat');
        $siparisler->unset_view(); 
        $siparisler->unset_edit(); 
        $siparisler->unset_add(); 
        $siparisler->unset_remove();
        $siparisler->unset_pagination();
        $siparisler->unset_search();

        return view('admin.goster')->with('xcrud',$xcrud);
    }



    public function topluMailGonder(Request $istek)
    {
        $dataMail = KullaniciBilgileri::where('uyelik_durumu','1')->pluck('mail_adresi');
        $mailSablonu = TopluMailSablonu::all();
        $konu = $mailSablonu['0']['konu'];
        
        $mail_adresleri = array();
        foreach ($dataMail as $mailadresi) {

            $bulunacak = array('ç','Ç','ı','İ','ğ','Ğ','ü','ö','Ş','ş','Ö','Ü',',',' ','(',')','[',']'); 
            $degistir  = array('c','C','i','I','g','G','u','o','S','s','O','U','','-','','','',''); 
             
            $sonuc=str_replace($bulunacak, $degistir, $mailadresi); // TR karektere dönüştür.
            
            array_push($mail_adresleri,$sonuc);
        }
        
        # $dataMail ==>> PHP Collection Item veriyor.
        # $mail_adresleri ==>> Sade bir diziye dönüşmüş hali.        
        
        Mail::send('admin.mail_icerik', [], function($message) use ($mail_adresleri, $konu)
        {//dd($mail_adresleri);
           $message->to($mail_adresleri)->subject($konu);
        });
        

        return redirect()->action('AdminController@topluMailIletisi')->with('durum', 'Mailiniz tüm üyelere başarılı şekilde gönderildi.');  
    }

}