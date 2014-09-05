<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<ul>
		<li>
			<a href="feed://www.cultura.gov.br/consultadireitoautoral/feed/" class="rss" title="Arquivo de Feed">Feed</a>
		</li>
		<li>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
		</li>
		<li>
			<button type="submit" id="searchsubmit">Busca...</button>
		</li>
	</ul>
</form>
