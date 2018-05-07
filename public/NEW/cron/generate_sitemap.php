<? 
require "/home/coinschedule/public_html/lib/bd.php";

$sql="SELECT * FROM tbl_data ORDER BY id ASC"; 
$listit1=mysqli_query($db,$sql);
$tot_cf=mysqli_num_rows($listit1);

echo "Creating Sitemap...<BR><BR>";

$content='<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$content.="<url> 
   <loc>http://www.coinschedule.com/</loc> 
   <lastmod>".(date("Y-m-d"))."</lastmod>
   <changefreq>daily</changefreq> 
</url>";
$content.="<url> 
   <loc>http://www.coinschedule.com/submit</loc> 
   <lastmod>".date_format(date_create("2016-08-01"),"Y-m-d")."</lastmod>
   <changefreq>daily</changefreq> 
</url>";



while($row=mysqli_fetch_array($listit1)){
	$id=$row['id'];
	$name=htmlspecialchars(strtolower(str_replace(" ",'-',$row['tx_name'])));
	$date=date("Y-m-d",strtotime($row['dt_change']));	
	if($row['dt_change']=="0000-00-00 00:00:00")$date=date_format(date_create("2016-08-01 00:00:01"),"Y-m-d");	
	$content.="<url> 
       <loc>http://www.coinschedule.com/$id/$name</loc> 
       <lastmod>$date</lastmod>
       <changefreq>daily</changefreq> 
   </url>";
}
$content.='</urlset>';

echo $content;

echo "<BR><BR>Saving Sitemap...<BR><BR>";
$fp = fopen('../sitemap.xml', 'w');
fwrite($fp, utf8_encode(($content)));
fclose($fp);


?>
