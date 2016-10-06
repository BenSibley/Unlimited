<div class='search-form-container'>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="search-field"><?php _e( 'Search', 'unlimited' ); ?></label>
		<input id="search-field" type="search" class="search-field"
		       placeholder="<?php esc_attr_e( 'Search...', 'unlimited' ); ?>" value="" name="s"
		       title="<?php esc_attr_e( 'Search for:', 'unlimited' ); ?>"/>
		<input type="submit" class="search-submit" value='<?php esc_attr_e( 'Go', 'unlimited' ); ?>'/>
	</form>
</div>