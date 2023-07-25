<?php

function get_temperature()
{
  if (strlen(urldecode($_GET['code_insee'])) > 0 && strlen(urldecode($_GET['ville']))>0){
    return "Error";
  }
  else if (strlen(urldecode($_GET['code_insee'])) == 0 && strlen(urldecode($_GET['ville']))==0){
    return "Blank Error";
  }
  if (strlen(urldecode($_GET['code_insee'])) > 0) {
    $ville = insee_code();
  } else {
    $ville = urldecode($_GET['ville']);
  }

  $ville = str_replace(" ", "+", $ville);
  $ville = iconv('UTF-8', 'ASCII//TRANSLIT', $ville);
  $curl = curl_init();
  curl_setopt_array(
    $curl,
    array(
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
      //CURLOPT_HTTPPROXYTUNNEL => true,
      //CURLOPT_PROXY => "127.0.0.1:3128"
    )
  );

  $response = curl_exec($curl);
  //echo $response;

  curl_close($curl);

  // Récupération de la température
  $response = stristr($response, '<div class="wtr_currTemp b_focusTextLarge" data-val="'); //Récupération de la portion de code
  $response = substr($response, 53); //Suppression de la chaine de recherche
  //Recherche de la fin de la température
  $val = 0;
  if (strlen($response) <= 20) {
    return "Unknown";
  }
  for ($i = 1; ; $i++) {
    if ($response[$i] == '"') {
      $val = $i;
      break;
    }
  }
  $response = substr($response, 0, $val); //Suppression des caractères après
  return $response;
}

echo get_temperature();

function insee_code()
{
  $db_host = 'localhost';
  $db_user = 'root';
  $db_password = 'root';
  $db_db = 'DataBase0';

  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );

  /*if ($mysqli->connect_error) {
    echo 'Errno: ' . $mysqli->connect_errno;
    echo '<br>';
    echo 'Error: ' . $mysqli->connect_error;
    exit();
  }

  echo 'Success: A proper connection to MySQL was made.';
  echo '<br>';
  echo 'Host information: ' . $mysqli->host_info;
  echo '<br>';
  echo 'Protocol version: ' . $mysqli->protocol_version;
*/
  $code_insee_recherche = urldecode($_GET['code_insee']);
  $sql = "SELECT * FROM meteo_insee WHERE INSEE = '$code_insee_recherche'";

  $result = $mysqli->query($sql);

  // Boucle pour parcourir les résultats
  while ($row = $result->fetch_assoc()) {
    $nom_ville = $row['LIBELLE'];
    // Faites ce que vous souhaitez avec les données récupérées
    //echo "Code INSEE : $code_insee, Nom de la ville : $nom_ville";
    //echo $nom_ville;
    return $nom_ville;
  }

  $mysqli->close();
  return 0;
}
?>