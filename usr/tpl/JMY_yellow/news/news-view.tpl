<div class="breadcrumbs smallPart blogghiamoanim fadeInUp animated" style="visibility: visible;">
	<table width=100%>
		<tr>
			<td width=60% align="left">
				{%BREADCUMB%}
			</td>
			<td width=40% style="text-align: right;">		
				{%RATING%}
			</td>
		</tr>
	</table>
</div>
<article class="hentry">
	<header class="entry-header">
		<h1 class="entry-title">{%TITLE%}</h1>
		<div class="entry-meta smallPart">
			<span class="posted-on">
				<i class="fa fa-calendar spaceRight" aria-hidden="true"></i>
				<time class="entry-date published" datetime="{%DATE%}">{%DATE%}</time>
			</span>
			<span class="byline">
				<i class="fa fa-user spaceRight" aria-hidden="true"></i>
				<span class="author vcard">
					[lang:_AUTHOR]: <strong>{%AUTHOR%}</strong>
				</span>
			</span>
			[category]
			<span class="cat-links smallPart">
				<i class="fa fa-folder-open spaceRight" aria-hidden="true"></i>
				{%CATEGORY%}
			</span>
			[/category]
			<span class="comments-link">
				<i class="fa fa-comments-o spaceRight" aria-hidden="true"></i>
				 [lang:_COMMENTS]: {%COMMENTS%}
			</span>
		</div>
	</header>		
	<div class="entry-content">
		{%FULL%}
	</div>
	<footer class="entry-footer smallPart">
		<span class="tags-links"><i class="fa fa-tags spaceRight" aria-hidden="true"></i> {%TAGS%} </span><br /><br />
		[related]<span class="tags-links"><i class="fa fa-tags spaceRight" aria-hidden="true"></i>[open]<b>[lang:_RELATED_NEWS]:</b><ul class="related">{%RELATED%}</ul>[/open]</span><br />[/related]
		{%EDIT%}
	</footer>	
</article>

		