
<?php
//$regex = '#\<div id="ires"\>(.+?)\<\/div\>#s';
//$url = "https://www.google.com/search?q=Jon+Toshmatov";
//$html	= @file_get_contents($url);
//preg_match('/<body(.*)<\/body>/si', $html, $html2);
//file_put_contents("files/google/results.txt", $html2);

$html	= @file_get_contents('files/google/results.txt');
if(!preg_match('/<div id="ires"><ol>(.*)<\/ol><\/div>/si', $html, $results)) return;

if(!preg_match('/<ol>(.*)<\/ol>/si', $results[0], $results2)) return;


$res = $results2[0];
if(!preg_match('/<div class="g">(.*)<\/div>/si', $res, $divs)) return;

$output = $divs[0];

$jon = explode('<div class="g">', $output);


$input = [];
foreach ($jon as $i=>$items){
    if ($items){
        //$h3 = preg_match('/<h3 class="r">(.*)<\/h3>/si', $items, $items99);
        $div3 = preg_match('/<div class="s">(.*)<\/div>/si', $items, $div99);
        //$input[$i]['title'] = $items99[0]??'';
        //$input[$i]['url'] = $items99[1]??'';
        $input[$i]['description'] = $div99[1]??'';
    }
}
echo "<pre>";
print_r($input);
echo "</pre>";
?>