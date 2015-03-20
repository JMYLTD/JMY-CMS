<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $fullajax;
$fullajax = array();
$fullajax['loader'] = "empt";
$fullajax['loaderStatic'] = "0";
$fullajax['replace'] = ":";
$fullajax['blockLinks'] = "get_photo,feed/,ajax,download";
$fullajax['storage'] = "0";
$fullajax['freeCode'] = "SRAX.Effect.add({id:&#039;fullAjax&#039;, start: function(id, request){
var opacity = new Fax.opacity(&#039;fullAjax&#039;,1,0.3,10,10); 
        opacity.afterEnd = request;
    },
    end: function(id){
         new Fax.opacity(&#039;fullAjax&#039;,0.3,1,10,10);
    }
}) ";

