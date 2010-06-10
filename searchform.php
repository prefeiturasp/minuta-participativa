<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
	<ul>
		<li>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
		</li>
		<li>
			<button type="submit" id="searchsubmit">busca</button>
		</li>
	</ul>
</form>
