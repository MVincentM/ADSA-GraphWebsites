<?php

require_once('crawler.class.php');

$crawler = new Crawler("https://www.clubic.com/", array(
    )
);
$crawler->crawl();
$crawler->displayResults();
$crawler->createFile();