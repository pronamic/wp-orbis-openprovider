<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	$orbis_subscriptions  = $this->plugin->get_orbis_subscriptions();
	$openprovider_domains = $this->plugin->get_openprovider_domains();

	// Sites
	$sites = array_unique(
		array_merge(
			array_keys( $orbis_subscriptions ),
			array_keys( $openprovider_domains )
		)
	);

	?>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th scope="col"><?php _e( 'Name', 'orbis_openprovider' ); ?></th>
				<th scope="col"><?php _e( 'Orbis', 'orbis_openprovider' ); ?></th>
				<th scope="col"><?php _e( 'Openprovider', 'orbis_openprovider' ); ?></th>
				<th scope="col"><?php _e( 'Status', 'orbis_openprovider' ); ?></th>
				<th scope="col"><?php _e( 'Autorenew', 'orbis_openprovider' ); ?></th>
			</tr>
		</thead>

		<tbody>
			
			<?php foreach ( $sites as $name ) : ?>

				<tr>
					<td>
						<?php echo $name; ?>
					</td>
					<td>
						<?php

						$dashicon = isset( $orbis_subscriptions[ $name ] ) ? 'yes' : 'no';

						printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );

						?>
					</td>
					<td>
						<?php

						$dashicon = isset( $openprovider_domains[ $name ] ) ? 'yes' : 'no';

						printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );

						?>
					</td>
					<td>
						<?php

						if ( isset( $openprovider_domains[ $name ] ) ) {
							$item = $openprovider_domains[ $name ];

							echo '<code>', $item->status, '</code>';
						}

						?>
					</td>
					<td>
						<?php

						if ( isset( $openprovider_domains[ $name ] ) ) {
							$item = $openprovider_domains[ $name ];

							echo '<code>', $item->autorenew, '</code>';
						}

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>
