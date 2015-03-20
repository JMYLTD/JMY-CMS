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

loadConfig('feedback'); 

			if(!empty($feedback_conf['keywords']))
			{
				$core->tpl->keywords =$feedback_conf['keywords'];
			}
			if(!empty($feedback_conf['description']))
			{
				$core->tpl->description = $feedback_conf['description'];
			}

switch(isset($url[1]) ? $url[1] : null) 
{
	default:
		set_title(array(_FEEDBACK));
		$core->tpl->loadFile('feedback');
		$core->tpl->setVar('CAPTCHA', captcha_image());
		$core->tpl->setVar('FILE_SIZE', formatfilesize($feedback_conf['file_size']));
		$core->tpl->setVar('FORMATS', $feedback_conf['formats']);
		$core->tpl->sources = preg_replace("#\\[load_attach\\](.*?)\\[/load_attach\\]#ies", "if_set('" . $feedback_conf['allow_attach'] . "', '\\1')", $core->tpl->sources);
		$core->tpl->end();
		break;	
	
	case "send":
		set_title(array(_FEEDBACK, _SENDINGMESS));
		if(captcha_check('securityCode')) 
		{
			$topic = isset($_POST['topic']) ? filter($_POST['topic']) : '';
			$email = isset($_POST['email']) ? filter($_POST['email']) : '';
			$name = isset($_POST['name']) ? filter($_POST['name']) : '';
			$message = isset($_POST['message']) ? filter($_POST['message'], 'html') : '';

			if(!empty($topic) && !empty($message)) 
			{				
			
				$regMsg .= 'Здравствуйте!' . "<br />";
				$regMsg .= 'Получено письмо из формы обратной связи. '. $name .' пишет:<br />';
				$regMsg .= '<hr><br>';
				$regMsg .= $message . "<br><br>";
				$regMsg .= '<hr><br>';
				if($core->auth->isUser)
				{
					$regMsg .= 'Пользователь: ' . $core->auth->user_info['nick'] .' | IP: ' . $_SERVER['REMOTE_ADDR'] . '<br>';
				}
				else
				{
					$regMsg .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . '<br>';
				}
				$regMsg .= 'E-mail для связи: ' . $email . '<br>';
				$regMsg .= '<br>---' . "<br>";
				$regMsg .= 'С уважением, администрация ' . $config['name'];
				
				
				if(isset($_FILES['attach']) && $foo = new Upload($_FILES['attach']))
				{
					if($feedback_conf['file_size'] >= $_FILES['attach']['size'])
					{
						$fileInfo = explode('.', $_FILES['attach']['name']);
						$fileFormat = mb_strtolower(end($fileInfo));
						$formats = explode(',', $feedback_conf['formats']);
						if(in_array($fileFormat, $formats))
						{
							$file_content = $foo->Process();
								
							if ($foo->processed) 
							{
								$foo->Clean();
								$regMsg .= "content-type: application/octet-stream;";
								$regMsg .= 'name="'.basename($_FILES['attach']['name']).''."\n";
								$regMsg .= "content-transfer-encoding:base64\n";
								$regMsg .= "content-disposition:attachment;\n\n";
								$regMsg .= chunk_split(base64_encode($file_content))."\n";
							}
						}
					}
				}
			
				sendMail($config['support_mail'], $topic . ' - '.$config['name'], $regMsg);
				$core->tpl->info(_SENDOK);
			} 
			else 
			{
				$core->tpl->info(_SENDFALSE, 'warning');
			}
		} 
		else 
		{
			$core->tpl->info(_CAPTCHAFALSE, 'warning');
		}
		break;
}