<div class="card mb-3">
	<div class="card-header">Openprovider DNS-records</div>

	<div class="card-body">
		<?php

		/**
		 * @link https://doc.openprovider.eu/API_Module_Nameserver_searchZoneRecordDnsRequest
		 * @link https://github.com/Werkspot/openprovider-dns-export
		 */
		$domain_name = get_the_title();

		$response = $this->get_openprovider_dns_records( $domain_name );

		$text_lines = array();

		if ( $response->reply->data->results->array ) {
			foreach ( $response->reply->data->results->array->item as $item ) {
				$name  = strval( $item->name );
				$type  = strval( $item->type );
				$value = strval( $item->value );
				$prio  = strval( $item->prio );
				$ttl   = strval( $item->ttl );
				
				if ( in_array( $type, array( 'SOA', 'NS' ), true ) ) {
					continue;
				}

				$name = str_replace( $domain_name, '', $name );
				$name = empty( $name ) ? '@' : substr( $name, 0, -1 );

				if ( ! empty( $prio ) ) {
					$value = $prio . ' ' . $value;
				}

				if ( in_array( $type, array( 'CNAME', 'MX' ), true ) ) {
					$value .= '.';
				}

				if ( 'TXT' === $type && '"' !== substr( $value, 0, 1 ) ) {
					$value = '"' . $value . '"';
				}

				$text_lines[] = sprintf(
					"%-20s\t%d\tIN\t%s\t%s",
					$name,
					$ttl,
					$type,
					$value
				);
			}
		}

		if ( $response->reply->data->results->array ) : ?>

			<table class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Type</th>
						<th scope="col">Value</th>
						<th scope="col">Prio</th>
						<th scope="col">TTL</th>
					</tr>
				</thead>

				<tbody>
					
					<?php foreach ( $response->reply->data->results->array->item as $item ) : ?>

						<tr>
							<td>
								<?php echo esc_html( strval( $item->name ) ); ?>
							</td>
							<td>
								<?php echo esc_html( strval( $item->type ) ); ?>
							</td>
							<td>
								<?php echo esc_html( strval( $item->value ) ); ?>
							</td>
							<td>
								<?php echo esc_html( strval( $item->prio ) ); ?>
							</td>
							<td>
								<?php echo esc_html( strval( $item->ttl ) ); ?>
							</td>
						</tr>

					<?php endforeach; ?>

				</tbody>
			</table>

		<?php endif; ?>

		<textarea class="form-control text-nowrap text-monospace" rows="10" cols="60"><?php echo esc_textarea( implode( "\r\n", $text_lines ) ); ?></textarea>

	</div>
</div>
