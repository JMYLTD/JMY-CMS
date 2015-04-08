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

function show_poll($type = false, $pollId = false)
{
global $db, $core, $list, $core;

	$query = $db->query('SELECT p.id as poll_id, p.title, p.votes, p.max, GROUP_CONCAT(q.id) as var_ids, GROUP_CONCAT(q.variant) as variants, GROUP_CONCAT(q.vote) as var_votes, v.uid, v.ip FROM ' . DB_PREFIX . '_polls AS p INNER JOIN  ' . DB_PREFIX . '_poll_questions AS q ON (p.id = q.pid) LEFT JOIN ' . DB_PREFIX . '_poll_voting as v ON (' . ($core->auth->isUser ? 'v.uid = ' . $core->auth->user_id : 'v.ip = \'' . getRealIpAddr() . '\'') . ' and v.pid = p.id) ' . ($pollId ? 'WHERE p.id = \'' . $pollId . '\' GROUP BY q.pid' : 'GROUP BY q.pid ORDER BY p.title'));
	if($db->numRows($query) > 0)
	{
		$poll = $db->getRow($query);
		$var_ids = explode(',', $poll['var_ids']);
		$variants = explode(',', $poll['variants']);
		$var_votes = explode(',', $poll['var_votes']);
		$vars = array();
		
		foreach ($var_ids AS $key => $val)
		{
			$vars[$val] = str_replace('||', ',', $variants[$key]);
			$var_vote[$val] = $var_votes[$key];
		}

		unset ($var_ids, $variants, $var_votes);

		$core->tpl->loadFile('poll');
		preg_match("#\\[viewList\\](.*?)\\[/viewList\\]#si", $core->tpl->sources, $matches);
		$viewList = $matches[1];
		$core->tpl->sources = preg_replace("#\\[viewList\\](.*?)\\[/viewList\\]#si", '', $core->tpl->sources);
		$core->tpl->setVar('TITLE', $poll['title']);
		$core->tpl->setVar('ID', $poll['poll_id']);
		$core->tpl->setVar('VOTES', $poll['votes']);
		
		$result = false;
		ksort($vars);

		switch($type)
		{
			default:
				if(($core->auth->isUser && $poll['uid'] == $core->auth->user_id) OR $poll['ip'] == getRealIpAddr())
				{
					$voted = true;
					$nvoted = false;
				}
				else
				{
					$voted = false;
					$nvoted = true;
				}
				
				$i = 0;
				
				foreach($vars as $id => $variant)
				{	
					$i++;
					
					if(($core->auth->isUser && $poll['uid'] == $core->auth->user_id) OR $poll['ip'] == getRealIpAddr())
					{
						if($poll['votes'] > 0)
						{
							$percent[$id] = round(($var_vote[$id]/$poll['votes'])*100);
						}
						else
						{
							$percent[$id] = 0;
						}
						
						$list .= str_replace(array('{%VARIANT%}', '{%VARVOTE%}', '{%PERCENT%}', '{%NUMB%}', '{%PERCENTWIDTH%}'), array($variant, $var_vote[$id], $percent[$id], $i, ($percent[$id] == 0 ? 2 : ($percent[$id]-5))), $viewList);
					}
					else
					{
						if($poll['max'] > 1)
						{
							$list .= '<div class="poll_check"><input type="checkbox" name="check[]" value="' . $id . '"  onclick="check(this, \'' . $poll['max'] . '\');" /> ' . $variant . ' </div>';
						}
						else
						{
							$list .= '<div class="poll_check"><input type="radio" name="check[]" value="' . $id . '" /> ' . $variant . ' </div>';
						}
					}

					if($i == 5)
					{
						$i = 0;
					}
				}
				break;
			
			case 'results':
				$result = true;
				
			case 'voted':
				$voted = true;
				$nvoted = false;
				
				$i = 0;
				foreach($vars as $id => $variant)
				{	
					$i++;
					
					if($poll['votes'] > 0)
					{
						$percent[$id] = round(($var_vote[$id]/$poll['votes'])*100);
					}
					else
					{
						$percent[$id] = 0;
					}

					$list .= str_replace(array('{%VARIANT%}', '{%VARVOTE%}', '{%PERCENT%}', '{%NUMB%}', '{%PERCENTWIDTH%}'), array($variant, $var_vote[$id], $percent[$id], $i, ($percent[$id] == 0 ? 2 : ($percent[$id]-5))), $viewList);
					
					if($i == 5)
					{
						$i = 0;
					}
				}
				break;
		}

		$core->tpl->setVar('LIST', $list);
		$core->tpl->sources = preg_replace( "#\\[voted\\](.*?)\\[/voted\\]#ies","if_set('".$voted."', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace( "#\\[novoted\\](.*?)\\[/novoted\\]#ies","if_set('".$nvoted."', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace( "#\\[result\\](.*?)\\[/result\\]#ies","if_set('".$result."', '\\1')", $core->tpl->sources);
		$core->tpl->end();
	}
	else
	{
		echo _BLOCK_POLL_EMPTY;
	}
}