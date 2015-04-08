<?php	

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ADMIN_ACCESS')) 
{
    header('Location: /');
    exit;
}	
		$configBox = array(
			'poll' => array(
				'varName' => 'poll_conf',
				'title' => _BLOCK_POLL,
				'groups' => array(
					'main' => array(
						'title' => _BLOCK_POLL,
						'vars' => array(
							'poll_id' => array(
								'title' => _BLOCK_POLL_ID,
								'description' => _BLOCK_POLL_ID_DESC,
								'content' => changePoll(),
							),	
							'poll_rand' => array(
								'title' => _BLOCK_POLL_RANDOM,
								'description' => _BLOCK_POLL_RANDOM_DESC,
								'content' => radio("poll_rand", $poll_conf['poll_rand']),
							),	
						)							
					),
				),
			),
		);
		
function changePoll()
{
global $config, $core, $db, $poll_conf;	
	$content = '<select name="{varName}">';
	$query2 = $db->query("SELECT * FROM `" . USER_PREFIX . "_polls`");
	if($db->numRows($query2) > 0) 
	{
		while($rows2 = $db->getRow($query2)) 
		{
			$sel = ($poll_conf['poll_id'] == $rows2['id']) ? 'selected' : '';
			$content .= '<option value="' . $rows2['id'] . '" ' . $sel . '>' . $rows2['title'] . '</option>';
		}
		
	}
	else
	{
		$content .= '<option value="0">'._BLOCK_POLLS_EMPTY.'</option>';
	}	
	$content .= '</select>';
	return $content;
}