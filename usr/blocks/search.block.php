<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/


if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
echo '<form role="search" method="post" name="search_form" class="search-form" action="search">
				<label>
					<span class="screen-reader-text">Поиск:</span>
					<input type="text" class="search-field" placeholder="Поиск …" value="" name="query" title="Поиск:">
				</label>
				<input type="submit" class="search-submit" value="Поиск">
			</form>';
		