<link href="{%THEME%}/styles/bb.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var textareaName = '{%NAME%}';</script>
<script type="text/javascript" src="usr/plugins/js/bb_editor.js"></script>
<ul class="_bb bbLeft">
	<li><a href="javascript:void(0)" onclick="insertCode('b')"><img src="media/bb/bold.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('u')"><img src="media/bb/underline.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('i')"><img src="media/bb/italic.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('s')"><img src="media/bb/crossout.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('justify')"><img src="media/bb/clear.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('right')"><img src="media/bb/right.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('center')"><img src="media/bb/center.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('left')"><img src="media/bb/left.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="showhide('{%NAME%}_codes');"><img src="media/bb/code.png" border="0" alt="" /></a>
	<div id="{%NAME%}_codes" class="bb_boxed" style="display:none; position:absolute;">
		<select onchange="if(this.value!='') {insertCode('code', this.value); showhide('{%NAME%}_codes');}"><option value="">-------</option><option value="php">PHP код</option><option value="sql">SQL код</option><option value="html">HTML код</option><option value="css">CSS код</option><option value="javascript">JavaScript код</option><option value="text">Другое</option></select>
	</div>
	</li>
	<li><a href="javascript:void(0)" onclick="insertCode('quote')"><img src="media/bb/quote.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertBB('[hr]')"><img src="media/bb/hr.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertBB('mail')"><img src="media/bb/mail.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="showhide('{%NAME%}_fonts');"><img src="media/bb/font.png" border="0" alt="" /></a>
		<div id="{%NAME%}_fonts" class="bb_boxed" style="display:none; position:absolute;">
			<select onchange="if(this.value!='') {insertCode('color', this.value); showhide('{%NAME%}_fonts');}"><option value="">Цвет</option><option value="red" style="color:red">Красный</option><option value="blue" style="color:blue">Синий</option><option value="black" style="color:black">Чёрный</option><option value="green" style="color:green">Зелёный</option><option value="white" style="color:white;background:grey;">Белый</option><option value="yellow" style="color:yellow;background:silver;">Жёлтый</option><option value="orange" style="color:orange">Оранжевый</option><option value="grey" style="color:grey">Серый</option></select>		
			<select onchange="if(this.value!='') {insertCode('size', this.value); showhide('{%NAME%}_fonts');}"><option value="">Размер</option><option value="1">1pt</option><option value="2">2pt</option><option value="3">3pt</option><option value="4">4pt</option><option value="5">5pt</option><option value="6">6pt</option><option value="7">7pt</option></select><!--<br />
			<select style="width:140px; margin-top:3px;"><option>Шрифт</option>
			</select>-->
		</div>
	</li>
	<li><a href="javascript:void(0)" onclick="insertBB('url')"><img src="media/bb/link.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertBB('image')"><img src="media/bb/image.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="showhide('{%NAME%}_smiles');"><img src="media/bb/smile.png" border="0" alt="" /></a><div id="{%NAME%}_smiles" class="bb_smiles" style="display:none; position:absolute;"><div align="justify">{%SMILE_LIST%}</div></div></li>
	[activeFlash]<li><a href="javascript:void(0)" onclick="insertCode('flash', '500x500')"><img src="media/bb/flash.png" border="0" alt="" /></a></li>[/activeFlash]
	[activeVideo]<li><a href="javascript:void(0)" onclick="insertCode('video')"><img src="media/bb/video.png" border="0" alt="" /></a></li>[/activeVideo]
	[activeAudio]<li><a href="javascript:void(0)" onclick="insertCode('audio')"><img src="media/bb/audio.png" border="0" alt="" /></a></li>[/activeAudio]
	<li><a href="javascript:void(0)" onclick="insertCode('spoiler', 'Заголовок')"><img src="media/bb/spoiler.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="insertCode('hide')"><img src="media/bb/hide.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="changeHeight('plus')"><img src="media/bb/zopmin.png" border="0" alt="" /></a></li>
	<li><a href="javascript:void(0)" onclick="changeHeight('minus')"><img src="media/bb/zoomout.png" border="0" alt="" /></a></li>

</ul>
<ul class="_bb bbRight">
	[activeHTML]<li><a href="javascript:void(0)" onclick="insertCode('html')"><img src="media/bb/html.png" border="0" alt="" /></a></li>[/activeHTML]
</ul>
<br style="clear:both" />
<div id="bb_area" onclick="hideBBPanel();">
<textarea cols="30" rows="{%ROWS%}" name="{%NAME%}" class="contactTextarea requiredField {%CLASS%}" id="{%NAME%}" {%onclick%} onclick="mainArea('{%NAME%}')">{%TEXT%}</textarea>
</div>
