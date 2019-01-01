<?php

require_once('findLinks.php');


define('EOL', "\r\n"); 

class Crawler
{   
    protected $_hostname     = null;
    protected $_options      = null;
    protected $_urlQueue     = array();
    protected $_urlProcessed = array();
    protected $_trieData     = array();

    public function __construct($hostname, array $options=null)
    {
        $this->_hostname = $hostname;

        $this->_options = array_merge(array(
            // Chaque option sera utilisée plus tard
            'userAgent'         => 'LLDC-Crawler-Vincent', // sera important pour ne pas fausser mes stats
            'fetchTimeout'      => 1000,
            'maxPages'          => 200,
            'maxErrors'         => 3,
            'showProgress'      => true,
            'showProgressEvery' => 1,
            'justHtml'          => true,
        ), $options);
    }

    public function crawl()
    {
        echo 'Starting crawl of host ['.$this->_hostname.']...'.EOL;
        echo 'Configuration : '.var_export($this->_options, true).EOL;
        echo EOL;

        $this->_urlQueue[] = $this->_hostname;

        $this->processQueue();
    }

    public function displayResults()
    {
        echo 'Results :'.EOL;
        echo "Nombre d'url exploré : ".count($this->_urlProcessed).EOL;
        // TODO
        // echo 'Not implemented'.EOL;

    }

    public function createFile()
    {
        echo 'Creation du fichier...'.EOL;
        $my_file = 'crawler.txt';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
        foreach($this->_trieData as $data)
        {
            foreach($data as $site)
            {
                $ecrire = ''.key($data).';'.$site.EOL;
                next($data);
                fwrite($handle, $ecrire);
            }
        }
        fclose($handle);
    }

    protected function trieData($parent,$enfants)
    {
        foreach($enfants as $enfant)
        {       
         $this->_trieData[] = array($parent => $enfant);
     }
 }

 protected function processQueue()
 { 
    while ( count($this->_urlQueue)>0 && count($this->_urlProcessed)<($this->_options[maxPages]))
    {
        echo "Nb d'urls dans la queue : ". count($this->_urlQueue).EOL;
        $urlToExplore = array_shift($this->_urlQueue);

        $this->_urlProcessed[] = $urlToExplore;
        echo "Url explorée : ".$urlToExplore.EOL;

        $content = file_get_contents($urlToExplore);

        list($liensInternes, $liensExternes) = findLinks($urlToExplore, $content);

        foreach ($liensInternes as $_lienInterne) 
        {  
            if (!in_array($_lienInterne, $this->_urlProcessed) && !in_array($_lienInterne, $this->_urlQueue))
            {
                $this->_urlQueue[] = $_lienInterne;
            }

        }
        $tab = array_merge($liensInternes,$liensExternes);
        $this->trieData($urlToExplore,$tab);
    }
}
}
