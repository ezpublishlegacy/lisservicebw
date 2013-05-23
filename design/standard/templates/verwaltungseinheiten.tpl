{ezscript_require( array( 'jquery.listnav-2.1.js' ) )}
{"listnav.css"|ezless_add()}

<div class="full full_lisservicebw">

	<div class="full_head">
		<h1>Verwaltungseinheiten</h1>
	</div>

	<div class="btn-toolbar" style="margin-bottom: 10px">
		<div class="btn-group">
	    	<a class="btn" href="/lisservicebw/lebenslagen"><i class="icon-align-justify"></i> Lebenslagen</a>
	    	<a class="btn" href="/lisservicebw/verfahren"><i class="icon-align-justify"></i> Verfahren</a>
	    	<a class="btn btn-inverse" href="/lisservicebw/verwaltungseinheiten"><i class="icon-align-justify"></i> Verwaltungseinheiten</a>
	  </div>
	</div>


{if $llid|ne('')}
	{def $verfahren = getVerfahren($id_verfahren)}
	<h3><i class="icon-caret-left"></i> <a href="/lisservicebw/verfahren?id={$id_verfahren}&amp;llid={$llid}&amp;type=detail">{$verfahren.verfahrenstitel}</a></h3>
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
		<h2>{$result.name}</h2>
		<p>{$result.beschreibung}</p>

		<h3>Hausanschrift</h3>
		{if $result.hausanschrift.strasse|ne('')}
			{$result.hausanschrift.strasse} {$result.hausanschrift.hausnummer}<br>
		{/if}
		{if $result.hausanschrift.ort|ne('')}
			{$result.hausanschrift.plz} {$result.hausanschrift.ort}<br>
		{/if}
		{if $result.telefon|ne('')}
			Tel.: {$result.telefon} Fax: {$result.fax}<br>
		{/if}
		{if $result.url|ne('')}
			Internet: <a href="{$result.url}" target='_blank'>{$result.url}</a><br>
		{/if}

		<br>
		<h3>Postanschrift</h3>
		{if $result.postanschrift.strasse|ne('')}
			{$result.postanschrift.strasse} {$result.postanschrift.hausnummer}<br>
		{/if}
		{if $result.postanschrift.ort|ne('')}
			{$result.postanschrift.plz} {$result.postanschrift.ort}<br>
		{/if}
		{if $result.telefon|ne('')}
			Tel.: {$result.telefon} Fax: {$result.fax}<br>
		{/if}
		{if $result.url|ne('')}
			Internet: <a href="{$result.url}" target='_blank'>{$result.url}</a><br>
		{/if}

		<br>
		<img src="https://www.service-bw.de/eBAdminCenter/loadimage?id={$result.id}&amp;type=Behoerde&amp;sprachId=deu&amp;uebersetzungsZustand=1" alt=""/>
	    <br>

	    {if $result.hausanschrift.zustaendigkeit|count() }
		    <h3>Zuständigkeit</h3>
			{$result.hausanschrift.zustaendigkeit}<br>
		{/if}

		{if $result.hausanschrift.anfahrtsbeschreibung|count() }
		    <h3>Anfahrt</h3>
			{$result.hausanschrift.anfahrtsbeschreibung}<br>
		{/if}

		{if $result.sprechzeiten|count() }
			<h3>Sprechzeiten</h3>
			{$result.sprechzeiten}<br><br>
		{/if}

		{if $result.hausanschrift.parkplatz|count() }
			<h3>Parkplatz</h3>
			{$result.hausanschrift.parkplatz}<br> <br>
		{/if}


		{if  $result.kontakt|count() }
			<h3>Persönliche Kontakte</h3>
			{foreach $result.kontakt as $kontakt}
				<p><b>{$kontakt.anrede} {$kontakt.vorname} {$kontakt.name}</b></p>
				Telefon: {$kontakt.telefon}<br>
				Fax: {$kontakt.fax}<br>
				Mail: {$kontakt.email}<br>
				Sprechzeit: {$kontakt.sprechzeiten}<br>
				Raum: {$kontakt.raum}<br>
				Zuständigkeit: {$kontakt.zustaendigkeit}<br>
			    <br>
			{/foreach}
		{/if}

		</div>
		</div>
	{else}

		<div id="myList-nav"></div>

		{if $result.return|count()}

			<div class="thumbnail thumbnail_attribute">
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