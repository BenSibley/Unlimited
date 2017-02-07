<div class='search-form-container'>
	<button id="search-icon" class="search-icon">
		<i class="fa fa-search"></i>
	</button>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text"><?php _ex( 'Search', 'verb', 'unlimited' ); ?></label>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search...', 'unlimited' ); ?>" value=""
		       name="s" title="<?php esc_attr_e( 'Search for:', 'unlimited' ); ?>" tabindex="-1"/>
	</form>
</div>