<?php

    function nice_input($value, $field, $primary_key, $list, $xcrud)
    {
       return '<div class="input-group">
                <span style="background-color:#e5e5e5;" class="input-group-addon" id="basic-addon3">http://www.websitename.com/</span>
                <input type="text" class="form-control" name="'.$xcrud->fieldname_encode($field).'" value="'.$value.'" id="basic-url" aria-describedby="basic-addon3">
                </div>';
    }

    function getUrunId($postdata,$primary_key)
    {
        $urun_adi = $postdata->get('sayfa_adi');

        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        
        $db->query("INSERT INTO urun_ozellikleri(urun_id, urun_adi, fiyat, aktiflik) VALUES('$primary_key','$urun_adi','-',1)");
    }

    function dilSecenekleriniOtomatikOlustur($postdata,$primary_key)
    {

        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        
        $db->query("INSERT INTO sabit_sayfalar_translate(sabit_sayfalar_id, dil) VALUES('$primary_key','en')");
        $db->query("INSERT INTO sabit_sayfalar_translate(sabit_sayfalar_id, dil) VALUES('$primary_key','ar')");
        $db->query("INSERT INTO sabit_sayfalar_translate(sabit_sayfalar_id, dil) VALUES('$primary_key','de')");
        $db->query("INSERT INTO sabit_sayfalar_translate(sabit_sayfalar_id, dil) VALUES('$primary_key','fr')");
		
        ## İlk LinkRecords kaydı buradan yapılıyor.
        $string = $postdata->get('sayfa_linki');
        $db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_id) VALUES('$string','$primary_key')");
    }

    function changeBrandName($postdata,$primary_key)
    {
        $urun_adi = $postdata->get('sayfa_adi');
        $urun_adi = addslashes($urun_adi);

        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        
        $db->query("UPDATE urun_ozellikleri SET `urun_adi`='$urun_adi' WHERE `urun_id`=$primary_key");
    }


    function changePicture($postdata,$primary_key)
    {
        $resim_linki = $postdata->get('resim_linki');

        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        
        $db->query("UPDATE sabit_sayfalar_translate SET `resim_linki`='$resim_linki' WHERE `sabit_sayfalar_id`=$primary_key");
    }

	
	function kayit_donusu(){
		echo "<script> alert('Üyelik kaydınız başarıyla gerçekleşti. Üye giriş sayfasına yönlendiriliyorsunuz...'); 
		window.location.href = 'giris-yap';
		</script>";
	}

	
	
	#################### LINK RECORDS VE TRANSLATE SEFLİNK ---------- BAŞLA ----------- ####################
	
	
    function urunlerSeflinkEkleTR($postdata, $xcrud)
    {	
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = 'urunlerimiz/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }

	function urunlerSeflinkGuncelleTR($postdata, $primary_key, $xcrud)
    {   
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = 'urunlerimiz/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
        
        /* ######################################### link_records tablo güncellemesi #########################################
         * 1) Eğer daha önce kaydı yapılmamışsa eski_link olarak kayıt yap. (else içine gir direkt)
         * 2) Daha önceden kayıt varsa if içine gir ve eski_link karşısına yenisini yaz.
         * 3) İkinci adımda güncellenen yeni_link değerini ayrıca, yeni bir kayıt olarak ekle. (1. adımdaki gibi. eski_link karşısı boş olc. şekilde)
        */
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT * FROM link_records WHERE `sabit_sayfalar_id`='$primary_key' AND yeni_link IS NULL ORDER BY id DESC");
        $veriler = $db->result(); # Link Records içerisinde böyle bir kayıt olup olmadığını kontrol ettik.
        
        //echo "<pre>"; print_r($veriler); echo "</pre>";
        
        if(!empty($veriler)){
            
            if(($veriler[0]['eski_link']) != $string){ # başlık değişmediyse, seflink de değişmemiştir, o halde link_records sayfasında guncellemeye gerek yok.
                $db->query("UPDATE link_records SET `yeni_link`='$string' WHERE `id`=".$veriler[0]['id']."");
                $db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_id) VALUES('$string','$primary_key')");
            }
            
        }else{ ##İlk defa kayıt oluşturacaksam.
            $db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_id) VALUES('$string','$primary_key')");
        }

        //$db->query("INSERT INTO link_records(eski_link, dil) VALUES('$string')");
    }

    function kurumsalSeflinkTranslate($postdata, $primary_key, $xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $dil = $postdata->get('dil'); // tablodan 'sayfa_adi' bilgisini al

        // echo "<script> alert('" .  $dil  . "'); </script>";

        if($dil == "ar"){
            $string = preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $string);
            $string = str_replace(' ', '-', $string);
            $string = $dil.'/'.$string;
        }else{
            $find = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
            
            $replace   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");
                

                $string = strtolower(str_replace($find, $replace, $string));
                $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
                $string = trim(preg_replace('/\s+/', ' ', $string));
                $string = str_replace(' ', '-', $string);
                $string = $dil.'/'.$string;
         }

        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }


    function urunlerSeflinkTranslate($postdata, $primary_key, $xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $dil = $postdata->get('dil'); // tablodan 'sayfa_adi' bilgisini al

        if($dil == "ar"){
            $string = preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $string);
            $string = preg_replace('/[\/]/', ' ', $string);
            $string = preg_replace("/\s+/", " ", $string);
            $string = trim($string);
            $string = str_replace(' ', '-', $string);
            $string = 'ar/'. $string . '/منتجات'; ##TERSSSSSSSSSSSSSSSS ARABBLAR!!!

        }else{
            $find = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
            
            $replace   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");

            $string = strtolower(str_replace($find, $replace, $string));
            $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
            $string = trim(preg_replace('/\s+/', ' ', $string));
            $string = str_replace(' ', '-', $string);
        
        
            if($dil == 'en'){
                $string = 'en/products/'. $string;

            }else if($dil == 'de'){
                $string = 'de/produkte/'.$string;
                
            }else if($dil == 'fr'){
                $string = 'fr/des-produits/'.$string;
                
            }else{
                $dil = "tanimsiz dil";
            }
        }
        
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
		linkRecordsForTranslate($string,$primary_key);
    }
	
	function linkRecordsForTranslate($string,$primary_key)
	{
		/* ######################################### link_records tablo güncellemesi #########################################
		 * 1) Eğer daha önce kaydı yapılmamışsa eski_link olarak kayıt yap. (else içine gir direkt)
		 * 2) Daha önceden kayıt varsa if içine gir ve eski_link karşısına yenisini yaz.
		 * 3) İkinci adımda güncellenen yeni_link değerini ayrıca, yeni bir kayıt olarak ekle. (1. adımdaki gibi. eski_link karşısı boş olc. şekilde)
		*/
		
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT * FROM link_records WHERE `sabit_sayfalar_translate_id`='$primary_key' AND yeni_link IS NULL ORDER BY id DESC");
        $veriler = $db->result(); # Link Records içerisinde böyle bir kayıt olup olmadığını kontrol ettik.
		
		//echo "<pre>"; print_r($veriler); echo "</pre>";
		
		if(!empty($veriler)){
			
			if(($veriler[0]['eski_link']) != $string){ # başlık değişmediyse, seflink de değişmemiştir, o halde link_records sayfasında guncellemeye gerek yok.
				$db->query("UPDATE link_records SET `yeni_link`='$string' WHERE `id`=".$veriler[0]['id']."");
				$db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_translate_id) VALUES('$string','$primary_key')");
			}
			
		}else{ ##İlk defa kayıt oluşturacaksam.
			$db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_translate_id) VALUES('$string','$primary_key')");
		}
	}


    function ALTurunlerSeflinkEkleTR($postdata, $xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $kategori_id = $postdata->get('ust_sayfa');
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT sayfa_linki FROM sabit_sayfalar WHERE id=".$kategori_id."");
        $veriler = $db->result(); 

        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = $veriler[0]['sayfa_linki'].'/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }
	
	function ALTurunlerSeflinkGuncelleTR($postdata, $primary_key, $xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $kategori_id = $postdata->get('ust_sayfa');
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT sayfa_linki FROM sabit_sayfalar WHERE id=".$kategori_id."");
        $veriler = $db->result(); 

        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = $veriler[0]['sayfa_linki'].'/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
        
        /* ######################################### link_records tablo güncellemesi #########################################
         * 1) Eğer daha önce kaydı yapılmamışsa eski_link olarak kayıt yap. (else içine gir direkt)
         * 2) Daha önceden kayıt varsa if içine gir ve eski_link karşısına yenisini yaz.
         * 3) İkinci adımda güncellenen yeni_link değerini ayrıca, yeni bir kayıt olarak ekle. (1. adımdaki gibi. eski_link karşısı boş olc. şekilde)
        */
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT * FROM link_records WHERE `sabit_sayfalar_id`='$primary_key' AND yeni_link IS NULL ORDER BY id DESC");
        $veriler = $db->result(); # Link Records içerisinde böyle bir kayıt olup olmadığını kontrol ettik.
        
        //echo "<pre>"; print_r($veriler); echo "</pre>";
        
        if(!empty($veriler)){
            
            if(($veriler[0]['eski_link']) != $string){ # başlık değişmediyse, seflink de değişmemiştir, o halde link_records sayfasında guncellemeye gerek yok.
                $db->query("UPDATE link_records SET `yeni_link`='$string' WHERE `id`=".$veriler[0]['id']."");
                $db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_id) VALUES('$string','$primary_key')");
            }
            
        }else{ ##İlk defa kayıt oluşturacaksam.
            $db->query("INSERT INTO link_records(eski_link, sabit_sayfalar_id) VALUES('$string','$primary_key')");
        }
    }


    function altSayfalarTranslateSeflinkEkle($postdata, $primary_key, $xcrud)
    {
        $string = $postdata->get('sayfa_adi');
        $sabit_sayfalar_id = $postdata->get('sabit_sayfalar_id');
        $dil = $postdata->get('dil');

        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("SELECT ust_sayfa FROM sabit_sayfalar WHERE id=".$sabit_sayfalar_id."");
        $veriler = $db->result(); 

        $db->query("SELECT sayfa_linki FROM sabit_sayfalar_translate WHERE sabit_sayfalar_id=".$veriler[0]['ust_sayfa']." AND dil='".$dil."'");
        $veriler = $db->result();

        if($dil == "ar"){
            mb_regex_encoding('UTF-8');

            $string = preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $string);
            $string = preg_replace('/[\/]/', ' ', $string);
            $string = preg_replace("/\s+/", " ", $string);
            $string = trim($string);
            $string = str_replace(' ', '-', $string);
            $string = $veriler[0]['sayfa_linki'] .'/'. $string; ##TERSSSSSSSSSSSSSSSS ARABBLAR!!!
            $ters   = explode( '/',$string);
            $string = $ters[0] . "/" . $ters[3] . "/" . $ters[1] . "/" . $ters[2];

            //echo "<script>alert('" . $ters[1] ."')</script>";
            //echo "<script>alert('" . $veriler[0]['sayfa_linki'] ."')</script>";

        }else{
            $find = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
            
            $replace   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");

            $string = strtolower(str_replace($find, $replace, $string));
            $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
            $string = trim(preg_replace('/\s+/', ' ', $string));
            $string = str_replace(' ', '-', $string);
            $string = $veriler[0]['sayfa_linki'] .'/'. $string;
        }

        $postdata->set('sayfa_linki', $string);
		linkRecordsForTranslate($string,$primary_key);
    }

	#################### LINK RECORDS VE TRANSLATE SEFLİNK ---------- SON ----------- ####################
	
	
	

    function ikSeflinkEkleTR($postdata,$xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = 'insan-kaynaklari/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }


    function sabitSayfaSeflinkEkleTR($postdata,$xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }

    function kategoriSeflinkEkleTR($postdata,$xcrud)
    {
        $string = $postdata->get('kategori'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $postdata->set('link', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }


     function galeriSeflinkEkleTR($postdata,$xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = 'galeri/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }      

    function hizmetlerSeflinkEkleTR($postdata,$xcrud)
    {
        $string = $postdata->get('sayfa_adi'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
        $string = 'hizmetlerimiz/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }    

	
	
    function blogSeflinkEkle($postdata,$xcrud)
    {
        $string = $postdata->get('baslik_tr'); // tablodan 'sayfa_adi' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
		$string = date('Y/m').'/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra 'sayfa_linki' sütununa ekle
    }
	
	function blogSeflinkEkleKategori($postdata,$xcrud)
    {
        $string = $postdata->get('kategori'); // tablodan 'kategori' bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
		$string = date('Y/m').'/'.$string;
        $postdata->set('link', $string); //baslik bilgisini dönüstürdükten sonra 'link' sütununa ekle
    }

    function blogSeflinkGuncelle($postdata,$xcrud)
    {
        $string = $postdata->get('baslik'); // tablodan baslik bilgisini al
        $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
        $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
        $string = strtolower(str_replace($find, $replace, $string));
        $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
        $string = trim(preg_replace('/\s+/', ' ', $string));
        $string = str_replace(' ', '-', $string);
		$string = date('Y/m').'/'.$string;
        $postdata->set('sayfa_linki', $string); //baslik bilgisini dönüstürdükten sonra link sütununa ekle
    }

	
	function guncelleme_donusu_sms()
    {
    	echo "<script> 
    		 alert('Göndermek istediğiniz toplu mail iletisinin içeriği başarılı bir şekilde güncellendi.');
    		</script>";
    }
	
	function guncelleme_donusu()
    {
    	echo "<script> 
    		 alert('Verileriniz başarılı bir şekilde güncellendi.');
    		</script>";
    }

    function iletisimformukayit($postdata)
    {
        $adsoyad = $postdata->get('if_adi');
        $eposta  = $postdata->get('if_eposta');
        $mesaj = $postdata->get('if_mesajiniz');
        $konu = $postdata->get('if_konu');
        $telefon = $postdata->get('if_telefon');
		
		require_once("PHPMailer/class.phpmailer.php");
		require_once("PHPMailer/PHPMailerAutoload.php");
		
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->SMTPDebug = 1;
		$mail->SMTPAuth = true;
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '587';
		$mail->SMTPSecure = 'tls';
		$mail->Username = 'test@gmail.com';
		$mail->Password = 'test123';
		$mail->SetFrom('test@gmail.com', 'İletişim Formu Mesajı');
		
		$mail->AddAddress('test1@gmail.com', 'test1@gmail.com');
		$mail->AddAddress('test2@gmail.com', 'test2@gmail.com');
		$mail->AddAddress('test3@gmail.com', 'test3@gmail.com');
		$mail->AddAddress('test4@gmail.com', 'test4@gmail.com');
		//$mail->AddCC('xx@cukurovapatent.com', 'xx');
		//$mail->AddCC('x@cukurovapatent.com', 'xx');
		
		$mail->CharSet = "UTF-8";
		$mail->Subject = $konu;
		$mail->IsHTML(true);
		$mail->MsgHTML("<b>Ad Soyad:</b> ". $adsoyad . "<br><b>Posta Adresi:</b> ". $eposta . "<br><b>Telefon:</b> ". $telefon ."<br><b>Mesaj:</b> " . $mesaj);		
		
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
		
		//Mesajınız başarıyla tarafımıza ulaştı. İlginiz için teşekkür ederiz.
        echo "<script> 
             alert('Success! Your message is sent.');
			 location.reload();
            </script>";
    }
    
    function seokategori($postdata,$xcrud)
    {
        $postdata->set('uk_seflink', seflink($postdata->get('uk_adi')) );
        
    }
    
    
    function seourun($postdata,$xcrud)
    {
        $postdata->set('u_seflink', seflink($postdata->get('u_adi')) );
        
    }
    
    
    function eklemelog($postdata,$primary,$xcrud){
        
        $tablo_adi = $xcrud->table_name;
        
        $bilgiler = "{";
        foreach($postdata->postdata as $key => $value)
        {
            if($bilgiler != "{"){$bilgiler .= ", ";}
            
            $bilgiler .= guvenlik($value);
        }
        
        $bilgiler .= "}";
        $islem = "ekleme";
        
        if(isset($_SESSION['k_id']))
        {
            $kullanici_id = $_SESSION['k_id'];
        }else{
            $kullanici_id = 0;
        }
        
        $aciklama = "$tablo_adi adlı tablo üstüne $bilgiler bilgiler eklendi.";
        
        $tarih = date("Y-m-d h:i:s");
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        
        $db->query("INSERT INTO islem_dizisi VALUES('','$aciklama','$islem','$bilgiler','$kullanici_id','$tarih')");
        
    }
    
    function duzenlemelog($postdata,$primary,$xcrud){
        
        $tablo_adi = $xcrud->table_name;
        
        $bilgiler = "{";
        foreach($postdata->postdata as $key => $value)
        {
            if($bilgiler != "{"){$bilgiler .= ", ";}
            
            $bilgiler .= guvenlik($value);
        }
        
        $bilgiler .= "}";
        $islem = "duzenleme";
        
        if(isset($_SESSION['k_id']))
        {
            $kullanici_id = $_SESSION['k_id'];
        }else{
            $kullanici_id = 0;
        }
        
        $aciklama = "$tablo_adi adlı tablo üzerinde bulunan $primary idli kayıt $bilgiler olarak güncellendi.";
        
        $tarih = date("Y-m-d h:i:s");
        
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
        $db->query("INSERT INTO islem_dizisi VALUES('','$aciklama','$islem','$bilgiler','$kullanici_id','$tarih')");
        
    }
    
    function silmelog($primary,$xcrud){
        
        
        $tablo_adi = $xcrud->table_name;
        $tablo = $xcrud->table;
        $pcol = $xcrud->primary_key;
        $pval = $xcrud->primary_val;
        
        // Bilgileri Oku
        $db = Xcrud_db::get_instance(); /* @var $db Xcrud_db */
           
        $db->query("SELECT * FROM $tablo WHERE $pcol='$pval'");
        $postdata = $db->result(); 
        
        $bilgiler = "{";
        foreach($postdata[0] as $key => $value)
        {
            if($bilgiler != "{"){$bilgiler .= ", ";}
            
            $bilgiler .= guvenlik($value);
        }
        
        $bilgiler .= "}";
        $islem = "silme";
        
        if(isset($_SESSION['k_id']))
        {
            $kullanici_id = $_SESSION['k_id'];
        }else{
            $kullanici_id = 0;
        }
        
        $aciklama = "$tablo_adi adlı tablo üzerinde bulunan $bilgiler bilgilerine ait $primary idli kayıt silindi.";
        $tarih = date("Y-m-d h:i:s");
        
        $db->query("INSERT INTO islem_dizisi VALUES('','$aciklama','$islem','$bilgiler','$kullanici_id','$tarih')");
        
    }

    
    function uyeol_formubef($postdata,$xcrud){
        
        $db = Xcrud_db::get_instance();
        $xcrud->set_exception("","Kategori Seçimi Yapılmamış lütfen bir önceki adıma dönerek kategori seçimini gerçekleştiriniz.");        
    }
    
    
    function uyeol_formu($postdata, $primary)
    {
        $db = Xcrud_db::get_instance(); /* @var $db xcrud */
     
        
        print_r($postdata);
        
        // Aktiflik Alanına Göre Bilgiler
        if(count($postdata->postdata)>0)
        {
            if($postdata->postdata['kullanicilar.k_aktiflik'] && $postdata->postdata['kullanicilar.k_aktiflik_2'])
            {
        
                $_SESSION['k_id'] = $db->insert_id();   
                $_SESSION['k_adi'] = $postdata->postdata['kullanicilar.k_adi'];
                $_SESSION['k_soyadi'] = $postdata->postdata['kullanicilar.k_soyadi'];
                $_SESSION['k_kadi'] = $postdata->postdata['kullanicilar.k_kadi'];
                $_SESSION['k_eposta'] = $postdata->postdata['kullanicilar.k_eposta'];
                $_SESSION['k_son_giris_tarihi'] = $postdata->postdata['kullanicilar.k_son_giris_tarihi'];
                $_SESSION['KULLANICI'] = true;
                
                $yetki_id = $postdata->postdata['kullanicilar.k_yetki_id'];
                
                $db->query("SELECT * FROM kullanici_yetkileri WHERE ky_id='$yetki_id'");
                $yetki_bilgi = $db->result(); 
                
                $_SESSION['yetki'] = $yetki_bilgi[0];
                
                bilgi("Başarıyla Giriş yapıldı... Yönlendiriliyorsunuz...","ok");
                
            }else{
                
                bilgi("Sistem üstünde kaydınız yaratıldı. Fakat onay yapılmadan üyeliğiniz tamamlanmayacaktır... Yönlendiriliyorsunuz...","uyari");
  
            }
        }    
    }    