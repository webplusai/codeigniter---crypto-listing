<?php

require_once("/home/coinschedule/public_html/scraper/CSProject.php");

$cs = new CSProject();

print_r($cs->scrapeIcoBench("https://icobench.com/icos?page=20&sort=raised-desc"));

?>