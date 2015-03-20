<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

function modal_box($title, $subtitle, $content, $id)
{
global $core;
	$core->tpl->headerIncludes['modal_css'] = '<link rel="stylesheet" href="usr/plugins/modal_box/modal_box.css" type="text/css" />'."\n";
	$core->tpl->headerIncludes['modal_js'] = '<script type="text/javascript" src="usr/plugins/modal_box/modal_box.js"></script>'."\n";
	echo '<script type="text/javascript">var title' . $id . ' = \'' . $title . '\'; var subtitle' . $id . ' = \'' . $subtitle . '\'; var content' . $id . ' = "' . addslashes($content) . '"; var nowModBox = \'\';</script>'."\n";
	echo '<div id="boxes' . $id . '"></div>'."\n";
}