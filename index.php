<?php

function get_temperature() {
  $ville = $_GET['ville'];
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.bing.com/search?q=m%C3%A9t%C3%A9o+$ville&form=QBLH&sp=-1&ghc=1&lq=0&pq=m%C3%A9t%C3%A9o+$ville&sc=10-14&qs=n&sk=&cvid=A94421AB031B43C3A6CFA2FAE6ECAAFC&ghsh=0&ghacc=0&ghpl=",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Cookie: MUID=142425C4C29067812CEF3688C3B96669; SRCHD=AF=QBLH; SRCHHPGUSR=SRCHLANG=fr; SRCHUID=V=2&GUID=2DC671511D0946E0B7BB6D46322D9A33&dmnchg=1; SRCHUSR=DOB=20230712; SUID=M; _EDGE_S=SID=0306CD2FEC13616E31F4DE7CEDA1607F; _EDGE_V=1; _SS=SID=0306CD2FEC13616E31F4DE7CEDA1607F; MUIDB=142425C4C29067812CEF3688C3B96669'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  
  // Récupération de la température
  $response = stristr($response, '<div class="wtr_currTemp b_focusTextLarge" data-val="'); //Récupération de la portion de code
  $response = substr($response, 53); //Suppression de la chaine de recherche
  //Recherche de la fin de la température
  $val = 0;
  for ($i = 1; ; $i++) {
    if($response[$i] == '"') {
      $val = $i;
      break;
    }
  }
  $response = substr($response, 0, $val); //Suppression des caractères après

  return $response;
}

echo get_temperature();
?>