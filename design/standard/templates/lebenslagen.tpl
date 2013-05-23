{ezscript_require( array( 'jquery.listnav-2.1.js' ) )}
{"listnav.css"|ezless_add()}

<div class="full full_lisservicebw">

	<div class="full_head">
		<h1>Lebenslagen</h1>
	</div>

	<div class="btn-toolbar" style="margin-bottom: 10px">
		<div class="btn-group">
	    	<a class="btn btn-inverse" href="/lisservicebw/lebenslagen"><i class="icon-align-justify"></i> Lebenslagen</a>
	    	<a class="btn" href="/lisservicebw/verfahren"><i class="icon-align-justify"></i> Verfahren</a>
	    	<a class="btn" href="/lisservicebw/verwaltungseinheiten"><i class="icon-align-justify"></i> Verwaltungseinheiten</a>
	  </div>
	</div>

	{if and($result.vorgaengerId|ne('8'), $result.vorgaengerId|ne('') )}
		<h3><a href="?id={$result.vorgaengerId}&amp;type=detail" ><i class="icon-caret-left"></i> {$result.vorgaengerTitel}</a></h3>
	{/if}

	{if eq($error, '')}

		{if $type|ne('detail')}


			<div id="myList-nav"></div>

			<div class="thumbnail thumbnail_atttribute">
				<div class="caption">

					{if $result.return|count()}

						 <ul id="myList">
							{foreach $result.return as $key => $res}
							  <li><a href="?id={$res[id]}&amp;type=detail">{$res[value]}</a></li>
							{/foreach}
						</ul>

					{/if}

				</div>
			</div>

		{else}

			<div class="thumbnail thumbnail_atttribute">
				<div class="caption">
					<h2>{$result.titel}</h2>
				    <p>{$result.untertitel}</p>
					<p>{$result.beschreibung}</p>

					{if $result.publizierteNachfolger|count()}

						 <ul id="myList">
							{foreach $result.publizierteNachfolger as $key=>$res }
							  <li><a href="?id={$res[id]}&amp;type=detail" >{$res[value]}</a></li>
							{/foreach}
						</ul>
					{/if}

					{if and(not($result.dienstleistungen.thema|count()),and($result.id|ne('8'), $result.id|ne('')) ) }
					  {if $result.dienstleistungen|count()}
					    <h4>Verfahren</h4>
						{foreach $result.dienstleistungen as $key=>$res }
						{if $res[thema]|ne('Zuordnung bitte unterlassen')}
						 	<a href="/lisservicebw/verfahren?id={$res[id]}&amp;llid={$result.id}&amp;type=detail" >{$res[value]}</a><br>
						{/if}
						{/foreach}
						{/if}
					{/if}
				</div>
			</div>

		{/if}


	{else}
		<div class="alert alert-error">{$error}</div>
		<a href="">Lebenslagen Übersicht</a>
	{/if}


<script>
	$('#myList').listnav();
</script>

</div>