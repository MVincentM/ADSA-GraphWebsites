<?php

// findLinks('www.apple.com', 'blabla <a href="//www.google.com/sdfs?fuck=me">Google</a>');

function findLinks($domain, $content)
{
    $internalLinks = array();
    $externalLinks = array();

    if (preg_match_all("#href=[\"\']([^\"])*[\"\']?#i", $content, $resultats))
	{
		for ($i=0;$i<count($resultats[0]);$i++)
		{

		    preg_match(";href=[\"\'](https?:)?(//)?([^:/]*)?(:\d*)?(/)?([^#]*)([#].*)*[\"\'];i", $resultats[0][$i], $urlParts);

            $_domain = isset($urlParts[3]) ? $urlParts[3]: '';
            $_port   = isset($urlParts[4]) ? $urlParts[4] : '';
            $_slash  = isset($urlParts[5]) ? $urlParts[5] : '';
            $_path   = isset($urlParts[6]) ? $urlParts[6] : '';
               
            if ( !empty($_domain) )
            {
                // Cas special du lien relatif 'href="tutu/titi"'
                if ( empty($urlParts[2]) )
                {
                    $correctPath = $_domain;
                    if ( !empty($_path)) $correctPath .= '/'.$_path;

                 $internalLinks[] = $domain.''.$correctPath;
                 //$internalLinks[] = 'http://'.$domain.'/'.$correctPath;

                }
                else
                {
                   // $url = 'http://'.$_domain.$_port.$_slash.$_path;
                     $url = $_domain.$_port.$_slash.$_path;

                    if ( $_domain==$domain )
                    {
                        $internalLinks[] = $url;
                    }
                    else
                    {
                        $externalLinks[] = $url;
                    }
                }
            }
            else
            {

                 $url = $domain.$_port.''.$_path;
                //$url = 'http://'.$domain.$_port.'/'.$_path;

                $internalLinks[] = $url;
            }

		}
              
        //var_export($internalLinks); 
        //var_export($externalLinks); 
    }


    // EVITE LES DOUBLONS
	$internalLinks = array_unique($internalLinks);
	$externalLinks = array_unique($externalLinks);

    // on retourne le resultat à l'appelant
    return array($internalLinks, $externalLinks);
}
