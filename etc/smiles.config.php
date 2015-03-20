<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global$smiles;
$smiles = array(':smile:'=>array('title'=>'Улыбка','url'=>'media/smiles/1.gif'),':sad:'=>array('title'=>'Расстроен','url'=>'media/smiles/2.gif'),':laugh:'=>array('title'=>'Смеётся','url'=>'media/smiles/3.gif'),':cool:'=>array('title'=>'Крутой','url'=>'media/smiles/4.gif'),':feel:'=>array('title'=>'Стесняется','url'=>'media/smiles/5.gif'),':belay:'=>array('title'=>'Удивлён','url'=>'media/smiles/6.gif'),':what:'=>array('title'=>'В замешательстве','url'=>'media/smiles/what.gif'),':drink:'=>array('title'=>'Бухаем','url'=>'media/smiles/drink.gif'),':hell:'=>array('title'=>'Не в себе','url'=>'media/smiles/aq.gif'),':crying:'=>array('title'=>'Плачет','url'=>'media/smiles/7.gif'),':happy:'=>array('title'=>'Счастлив','url'=>'media/smiles/8.gif'),'winked'=>array('title'=>'Поражн','url'=>'media/smiles/winked.gif'));
