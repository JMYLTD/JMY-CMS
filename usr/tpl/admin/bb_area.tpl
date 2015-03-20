<script type="text/javascript">var textareaName = '{%NAME%}';</script>
<script type="text/javascript" src="usr/plugins/js/bb_editor.js"></script>
<div class="form-group">
<div id="alerts"></div>
<div class="btn-toolbar mg-b" data-role="editor-toolbar" data-target="#editor">
<div class="btn-group">
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('b')" title data-original-title="Strikethrough"><i class="fa fa-bold"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('i')"><i class="fa fa-italic"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('s')"><i class="fa fa-strikethrough"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('u')"><i class="fa fa-underline"></i></a>
</div>

<div class="btn-group">
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('left')"><i class="fa fa-align-left"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('center')"><i class="fa fa-align-center"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('right')"><i class="fa fa-align-right"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('justify')"><i class="fa fa-align-justify"></i></a>
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code"></i><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'php')">PHP код</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'sql')">SQL код</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'html')">HTML код</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'css')">CSS код</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'javascript')">JavaScript код</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('code', 'text')">Другое</a></li>
		</ul>
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-text-height"></i><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="javascript:void(0)" onclick="insertCode('size', '1')">1pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '2')">2pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '3')">3pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '4')">4pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '5')">5pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '6')">6pt</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('size', '7')">7pt</a></li>			
		</ul>
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-font"></i><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'red')" style="color:red">Красный</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'blue')" style="color:blue">Синий</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'black')" style="color:black">Чёрный</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'green')" style="color:green">Зелёный</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'white')" style="color:white;background:silver;">Белый</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'yellow')" style="color:yellow;background:silver;">Жёлтый</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'orange')" style="color:orange">Оранжевый</a></li>
			<li><a href="javascript:void(0)" onclick="insertCode('color', 'grey')" style="color:grey">Серый</a></li>
		</ul>
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertBB('image')"><i class="fa fa-image"></i></a>
	[activeFlash]<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('flash')"><i class="fa fa-flash"></i></a>[/activeFlash]
	[activeVideo]<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('video')"><i class="fa fa-video-camera"></i></a>[/activeVideo]
	[activeAudio]<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('audio')"><i class="fa fa-volume-up"></i></a>[/activeAudio]
	[activeHTML] <a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('html')"><i class="fa fa-file-text-o"></i></a>[/activeHTML]

</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('quote')"><i class="fa fa-quote-right"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertBB('[hr]')"><i class="fa fa-minus"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('spoiler', 'Заголовок')"><i class="fa fa-plus-square"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertCode('hide')"><i class="fa fa-lock"></i></a>
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertBB('mail')"><i class="fa fa-envelope-square"></i></a>
	<a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="insertBB('url')"><i class="fa fa-link"></i></a>	
</div>
<div class="btn-group">
	<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-smile-o"></i><b class="caret"></b></a>
		<ul class="dropdown-menu">
			{%SMILE_LIST%}
		</ul>
</div></div>
<div id="bb_area" onclick="hideBBPanel();">
<textarea  style="margin-top: 0px; margin-bottom: 0px; height: 194px;" rows="{%ROWS%}" name="{%NAME%}" class="form-control {%CLASS%}" id="{%NAME%}" {%onclick%} onclick="mainArea('{%NAME%}')">{%TEXT%}</textarea>
</div>
</div>