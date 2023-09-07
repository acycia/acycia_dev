<?php require_once('Connections/conexion1.php'); ?>
<?php 
function  getBrowser()
    {
        $U_agent  = $_SERVER [ 'HTTP_USER_AGENT' ];
        $Ub  = '' ;
        if (preg_match ( '/MSIE/i' , $U_agent ))
        {
            $Ub  = "Internet Explorer" ;
        }
        else if (preg_match ( '/Firefox/i' , $U_agent))
        {
            $Ub  = "Mozilla Firefox" ;
        }
        else if (preg_match ( '/Safari/i' , $U_agent ))
        {
            $Ub  = "Apple Safari" ;
        }
        else if (preg_match ( '/Chrome/i' , $U_agent))
        {
            $Ub  = "Google Chrome" ;
        }
        else if (preg_match ( '/Flock/i' , $U_agent ))
         {
            $Ub  = "rebaño" ;
        }
        else if (preg_match ( '/Opera/i' , $U_agent ))
        {
            $Ub  = "Opera" ;
        }
        else if (preg_match ( '/Netscape/i' , $U_agent ))
        {
            $Ub  = "Netscape" ;
        }
        return $ub ;
    }
?>