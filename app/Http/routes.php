<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
| Made by Ali ARSLAN. Computer Engineer.
| aliarslan10@yandex.com.tr
*/

Route::get('/master', function () {
    return view('master');
});


Route::get('/', 'AnasayfaController@anasayfa');
Route::get('/en', 'AnasayfaController@anasayfa');
Route::get('/ar', 'AnasayfaController@anasayfa');
Route::get('/fr', 'AnasayfaController@anasayfa');
Route::get('/de', 'AnasayfaController@anasayfa');

Route::post('/', 'AnasayfaController@ebulten');
Route::post('/urun-arama', 'AnasayfaController@ara');
Route::get('/urun-arama', function () {
    return redirect('urunlerimiz');
});

Route::get('/404', function () {
    return view('errors.404');
});

############################ ADMİN PANELİ ##############################
Route::group(['prefix' => 'ckadmin'], function () {
	Route::get('/', 'AdminGoruntuleController@Admin');
	Route::get('/giris', 'AdminGoruntuleController@girisSayfasi');
	Route::post('/giris', 'AdminGoruntuleController@Login');
	Route::get('/cikis', 'AdminGoruntuleController@LogOut'); 
	
	Route::get('/kurumsal-sayfa-yonetimi', 'AdminController@kurumsal');
	Route::get('/galeri', 'AdminController@galeri');
	Route::get('/anasayfa-ayarlari', 'AdminController@anasayfaAyarlari');
	Route::get('/slider-yonetimi', 'AdminController@sliderYonetimi');
	Route::get('/iletisim-bilgileri', 'AdminController@iletisimBilgileri');
	Route::get('/insan-kaynaklari', 'AdminController@insanKaynaklari');
	
	Route::get('/kullanici-yonetimi', 'AdminController@kullanicilar');
	Route::post('/reset', 'AdminController@sifremiUnuttum');
	
	Route::get('/blog-kategorileri', 'AdminController@blogKategori');
	Route::get('/blog-icerikleri', 'AdminController@blogICerik');
	Route::get('/hizmet-yonetimi', 'AdminController@hizmetler');
	Route::get('/urun-kategori-yonetimi', 'AdminController@urunKategorileri');
	Route::get('/urun-yonetimi', 'AdminController@urunler');
	Route::get('/firsat-urunleri', 'AdminController@firsatUrunleri');
	
	Route::get('/toplu-mail-gonderimi', 'AdminController@topluMailIletisi');
	Route::post('/toplu-mail-gonderimi', 'AdminController@topluMailGonder');
	Route::get('/aa', 'AdminController@topluMailSablonu');
	Route::get('/menuler', 'AdminController@menuler');
	Route::get('/siparisler', 'AdminController@siparisler');
});
###############################################################################



############################ SABİT SAYFALAR & BLOG İŞLEMLERİ ##################
Route::group(['prefix' => 'arsiv'], function () {
	Route::get('/' ,'SabitSayfalarController@arsiv');
	Route::post('/','SabitSayfalarController@arsivGoruntule');
});

Route::group(['prefix' => 'hizmetlerimiz'], function () {
	Route::get('/{hizmet_adi?}', 'SabitSayfalarController@hizmetler');
});


############################ ÜRÜNLERİMİZ ############################
Route::group(['prefix' => 'urunlerimiz'], function () {
	Route::get('/{link1?}/{link2?}/{link3?}', 'SabitSayfalarController@urunler');
});

Route::group(['prefix' => 'en/products'], function () {
	Route::get('/{link1?}/{link2?}/{link3?}', 'SabitSayfalarController@urunler');
});

Route::group(['prefix' => 'de/produkte'], function () {
	Route::get('/{link1?}/{link2?}/{link3?}', 'SabitSayfalarController@urunler');
});

Route::group(['prefix' => 'fr/des-produits'], function () {
	Route::get('/{link1?}/{link2?}/{link3?}', 'SabitSayfalarController@urunler');
});

// "ar/عطور-والنكهات/منتجات"    linkten gelen arapça ifalerde parametreler yer değiştirerek geliyor.

Route::group(['prefix' => 'ar'], function () {
	Route::get('/منتجات', 'SabitSayfalarController@urunler');
	Route::get('/{link1?}/منتجات', 'SabitSayfalarController@urunler');
	Route::get('/{link1?}/{link2?}/منتجات', 'SabitSayfalarController@urunler');
	Route::get('/{link1?}/{link2?}/{link3?}/منتجات', 'SabitSayfalarController@urunler');
});
####################################################################################


Route::group(['prefix' => 'galeri'], function () {
	Route::get('/{id?}', 'SabitSayfalarController@galeri');
});

Route::group(['prefix' => 'insan-kaynaklari'], function () {
	Route::get('/{id?}', 'SabitSayfalarController@insanKaynaklari');
});

Route::group(['prefix' => 'haberler'], function () {
	Route::get('/kategoriler/{kategori_adi}','BlogController@blogKategoriGosterimi');
	Route::get('/{yil?}/{ay?}/{icerik?}','BlogController@blog');
});


################################## İLETİŞİM ##################################
Route::group(['prefix' => '/iletisim'], function () {
	Route::get('/','SabitSayfalarController@iletisimSayfasi');
});

Route::group(['prefix' => 'en/contact'], function () {
	Route::get('/','SabitSayfalarController@iletisimSayfasi');
});

Route::group(['prefix' => 'ar/اتصل'], function () {
	Route::get('/','SabitSayfalarController@iletisimSayfasi');
});

Route::group(['prefix' => 'de/kontakt'], function () {
	Route::get('/','SabitSayfalarController@iletisimSayfasi');
});

Route::group(['prefix' => 'fr/contact'], function () {
	Route::get('/','SabitSayfalarController@iletisimSayfasi');
});
###############################################################################


############################ KULLANICI İŞLEMLERİ ##############################
Route::get('/kayit-ol', 'KullaniciController@xcrudKayitSayfasi');
Route::post('/kayit-ol', 'KullaniciController@kayitOl');
Route::post('/kayit-basarili', 'KullaniciController@kayitBasarili');
Route::get('/kayit-basarili', 'KullaniciController@kayitBasarili');
Route::get('/giris-yap', 'KullaniciController@loginSayfasi');
Route::post('/giris-yap', 'KullaniciController@girisYap');
Route::get('/sifremi-unuttum', 'KullaniciController@sifremiUnuttumSayfasi');
Route::post('/sifremi-unuttum', 'KullaniciController@sifremiUnuttum');
Route::get('/kullanici-paneli', 'KullaniciController@kullaniciPaneli');
Route::get('/bilgilerim', 'KullaniciController@kullaniciBilgileri');
Route::get('/mesaj-at', 'KullaniciController@mesajAt');
Route::get('/cikis', 'KullaniciController@cikis');
###############################################################################

###############################################################################
# Desktop #
Route::get('/desktop','AnasayfaController@desktop');
Route::get('/{dil?}/{id?}', 'SabitSayfalarController@goruntule');
