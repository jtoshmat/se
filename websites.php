<?php
include_once 'word.php';
if (!isset($argv[1])){
    echo "1)com  2)en 3)-". PHP_EOL;
    exit;
}


$domain=$argv[1]??'com';
$language=$argv[2]??'en';
$domain='.'.$domain;

echo "Started: ";
echo date('i:s');
echo " | ";
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "searchengine";
$conn = new mysqli($servername, $username, $password, $dbname);
$urls = [];
for($i=0; $i<100000; $i++){
 $rn = rand(6,30);

 //$url=random_pronounceable_word($rn);
    $url = random_word();



    //.com
    if ($html=urlExists($url, $domain)){
     $favicon = get_favicon($url, $domain)??null;
     $inserted = insert_to_table($conn, $url, $html, $domain, $favicon, $language);
     if ($inserted){
         echo $i.' '.$url. "$domain has been inserted". PHP_EOL;
     }else{
         echo $i.' '.$url. "$domain duplicate is found". PHP_EOL;
     }
    }else{
     echo '!!!'.$i.':'.$domain. PHP_EOL;
    }

    get_screenshot($url, $domain);

}
function random_pronounceable_word($length) {
    
    // consonant sounds
    $cons = array(
        // single consonants. Beware of Q, it's often awkward in words
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
        'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z',
        // possible combinations excluding those which cannot start a word
        'pt', 'gl', 'gr', 'ch', 'ph', 'ps', 'sh', 'st', 'th', 'wh', 
    );
    
    // consonant combinations that cannot start a word
    $cons_cant_start = array( 
        'ck', 'cm',
        'dr', 'ds',
        'ft',
        'gh', 'gn',
        'kr', 'ks',
        'ls', 'lt', 'lr',
        'mp', 'mt', 'ms',
        'ng', 'ns',
        'rd', 'rg', 'rs', 'rt',
        'ss',
        'ts', 'tch', 
    );
    
    // wovels
    $vows = array(
        // single vowels
        'a', 'e', 'i', 'o', 'u', 'y', 
        // vowel combinations your language allows
        'ee', 'oa', 'oo', 
    );
    
    // start by vowel or consonant ?
    $current = ( mt_rand( 0, 1 ) == '0' ? 'cons' : 'vows' );
    
    $word = '';
        
    while( strlen( $word ) < $length ) {
    
        // After first letter, use all consonant combos
        if( strlen( $word ) == 2 ) 
            $cons = array_merge( $cons, $cons_cant_start );
 
         // random sign from either $cons or $vows
        $rnd = ${$current}[ mt_rand( 0, count( ${$current} ) -1 ) ];
        
        // check if random sign fits in word length
        if( strlen( $word . $rnd ) <= $length ) {
            $word .= $rnd;
            // alternate sounds
            $current = ( $current == 'cons' ? 'vows' : 'cons' );
        }
    }
    
    return $word;
}
function urlExists($url=NULL,$domain='.com')
{
    $url = $url.$domain;
    if($url == NULL) return false;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode>=200 && $httpcode<=301){
        return $data;
    } else {
        return false;
    }
}
function insert_to_table($conn, $word, $html,$domain='.com',$favicon=null,$lang='en'){
    $word=$word.$domain;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM dictionary where word='$word'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sql = "UPDATE dictionary SET word='$word',html='$html',favicon='$favicon',langguage='$lang' where word='$word'";
        $conn->query($sql);
        return false;
    }
    $sql = "INSERT IGNORE INTO dictionary (word, html, favicon,language) VALUES ('$word','$html','$favicon','$lang')";
    $conn->query($sql);
    return true;
}
function get_favicon($url,$domain='.com'){
    $url=$url.$domain;
    $url = 'http://'.$url."/favicon.ico";
    $headers = get_headers($url);
    if(preg_match("|200|", $headers[0])) {
        return $url;
    } else {
        return false;
    }
}
function get_screenshot($url, $domain='.com'){
//website url

    $rn = rand();
    $img_name = $url;


    $url='http://'.$url.$domain;
        $googlePagespeedData = @file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");

        //decode json data
        $googlePagespeedData = json_decode($googlePagespeedData, true);

        //screenshot data
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);

        //display screenshot image

        $img = "data:image/jpeg;base64,".$screenshot;
        file_put_contents('img/'.$img_name.'.jpg',$img);

}
$conn->close();
echo " | Completed: ";
echo date('i:s');
?>
