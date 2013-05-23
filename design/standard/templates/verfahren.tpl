{ezscript_require( array( 'jquery.listnav-2.1.js' ) )}
{"listnav.css"|ezless_add()}

<div class="full full_lisservicebw">

	<div class="full_head">
		<h1>Verfahren</h1>
	</div>

	<div class="btn-toolbar" style="margin-bottom: 10px">
		<div class="btn-group">
	    	<a class="btn" href="/lisservicebw/lebenslagen"><i class="icon-align-justify"></i> Lebenslagen</a>
	    	<a class="btn btn-inverse" href="/lisservicebw/verfahren"><i class="icon-align-justify"></i> Verfahren</a>
	    	<a class="btn" href="/lisservicebw/verwaltungseinheiten"><i class="icon-align-justify"></i> Verwaltungseinheiten</a>
	  </div>
	</div>

	{*def $lebenslage = getLLDetails($llid)*}
	{if $lebenslage[titel]|ne('Einleitung')}
		<h3><i class="icon-caret-left"></i> <a href="/lisservicebw/lebenslagen?id={$llid}&amp;type=detail"> {$lebenslage[titel]}</a></h3>
	{/if}

{if $error|ne('')}
	<div class="alert alert-danger">{$error}</div>

	{if and($result.id|ne('8'), $result.id|ne('') )}
		<a href="?id=8" >Lebenslagen Übersicht</a>
	{/if}
{else}

	{if $type|eq('detail')}

		<div class="thumbnail thumbnail_attribute">
		<div class="caption">
		<h2>{$result.verfahrenstitel}</h2>
		{if $verfahren|count()}
		    <br/>
			{foreach $verfahren as $k => $v}
				{if $v[thema]|ne('Zuordnung bitte unterlassen')}
					<a href="/lisservicebw/verfahren?id={$k}">{$v.value}</a><br/>
				{/if}
			{/foreach}
		{/if}

		{if $result.ablauf|count()}
			<h3>Verfahrensablauf</h3>
			<p>{$result.ablauf}</p>
		{/if}

		{*<h3>Zugehörigkeit zu Lebenslagen</h3>*}

		{if $result.zustaendigkeit|count()}
			<h3>Zuständikgeit</h3>
			<p>{$result.zustaendigkeit}</p>
		{/if}

		{if $verw|count}
			{if $verw[0]|count()}
				<br/>
				<h3>Zuständige Verwaltungseinheiten</h3>
				{foreach $verw as $v}
					{if $from|eq('list')}
						<a href="/lisservicebw/verwaltungseinheiten?id={$v[id]}&amp;id_verfahren={$id_verfahren}&amp;type=detail">{$v.value}</a><br>
					{else}
						<a href="/lisservicebw/verwaltungseinheiten?id={$v[id]}&amp;id_verfahren={$id_verfahren}&amp;llid={$llid}&amp;type=detail">{$v.value}</a><br>
					{/if}
				{/foreach}

			{else}
				<h3>Zuständige Verwaltungseinheiten</h3>
				{if $from|eq('list')}
					<a href="/lisservicebw/verwaltungseinheiten?id={$verw[id]}&amp;id_verfahren={$id_verfahren}&amp;type=detail">{$verw.value}</a><br>
				{else}
					<a href="/lisservicebw/verwaltungseinheiten?id={$verw[id]}&amp;id_verfahren={$id_verfahren}&amp;llid={$llid}&amp;type=detail">{$verw.value}</a><br>
				{/if}
			{/if}
		{/if}

		{if $result.voraussetzungen|count()}
		    <br>
			<h3>Voraussetzungen</h3>
			<p>{$result.voraussetzungen}</p>
		{/if}

		{if $result.informationen|count()}
			<br/>
			<h3>Informationen</h3>
			<p>{$result.informationen}</p>
		{/if}

		{if $result.unterlagen|count()}
			<br/>
			<h3>Erforderliche Unterlagen</h3>
			<p>{$result.unterlagen}</p>
		{/if}

		{if $result.gebuehren|count()}
		    <br/>
			<h3>Gebühren</h3>
			<p>{$result.gebuehren}</p>
		{/if}

		{if $result.rechtsgrundlage|count()}
			<h3>Rechtsgrundlage</h3>
			<p>{$result.rechtsgrundlage}</p>
		{/if}

		{if $result.freigabevermerk|count()}
			<h3>Freigabevermerk</h3>
			<p>{$result.freigabevermerk}</p>
		{/if}

		</div>
		</div>

	{else} {*alle Verfahren*}


		<div id="myList-nav"></div>

		{if $result.return|count()}
			<div class="thumbnail thumbnail_atttribute">
				<div class="caption">
				<ul id="myList">
					{foreach $result.return as $key=>$res }
					  <li><a href="?id={$res[id]}&amp;type=detail&amp;from=list" >{$res[value]}</a></li>
					{/foreach}
				</ul>
				</div>
			</div>
		{/if}

	{/if}



{/if}

<script>
	$('#myList').listnav();
</script>

</div>