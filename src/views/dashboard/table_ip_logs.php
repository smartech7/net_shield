<?php defined('SHIELDON_VIEW') || exit('Life is short, why are you wasting time?'); ?>

<?php

$timezone = '';

?>

<div class="so-dashboard">
    <div id="so-table-loading" class="so-datatables">
		<div class="lds-css ng-scope">
			<div class="lds-ripple">
				<div></div>
				<div></div>
			</div>
		</div>
	</div>
	<div id="so-table-container" class="so-datatables" style="display: none;">
        <div class="so-databable-heading">
            IP Log Table
        </div>
		<div class="so-datatable-description">
            <?php _e("This is where the Shieldon records the users' strange behavior.", 'wp-shieldon'); ?> 
            All processes are automatic and instant, you can ignore that.<br />
			IP log table will be all cleared after new cycle begins.
		</div>
		<table id="so-datalog" class="cell-border compact stripe" cellspacing="0" width="100%">
			<thead>
                <tr>
					<th rowspan="2">IP</th>
                    <th rowspan="2">Resolved hostname</th>
					<th colspan="4" class="merged-field">Pageviews</th>
                    <th colspan="3" class="merged-field">Flags</th>
					<th rowspan="2">Last visit</th>
				</tr>
				<tr>
					<th>S</th>
					<th>M</th>
                    <th>H</th>
                    <th>D</th>
					<th>Cookie</th>
					<th>Session</th>
                    <th>Referrer</th>
				</tr>
			</thead>
			<tbody>
                <?php foreach($ip_log_list as $ip_info ) : ?>
                    <?php $log_data = is_array($ip_info['log_data']) ? $ip_info['log_data'] : json_decode($ip_info['log_data'], true ); ?>
                    <?php

                        $text_warning = '';

                        if ($log_data['pageviews_m'] > 6 || $log_data['pageviews_h'] > 50 || $log_data['pageviews_d'] > 100 ) {
                            $text_warning = '<span class="so-text-warning"><i class="fas fa-exclamation-triangle"></i></span>';
                        }

                        if ($log_data['flag_js_cookie'] > 2 || $log_data['flag_multi_session'] > 2 || $log_data['flag_empty_referer'] > 2 ) {
                            $text_warning = '<span class="so-text-warning"><i class="fas fa-exclamation-triangle"></i></span>';
                        }

                        if ($log_data['flag_js_cookie'] > 3 || $log_data['flag_multi_session'] > 3 || $log_data['flag_empty_referer'] > 3 ) {
                            $text_warning = '<span class="so-text-danger"><i class="fas fa-exclamation-triangle"></i></span>';
                        }
                    ?>
                    <tr>
                        <td><?php echo $ip_info['log_ip']; ?><?php echo $text_warning; ?></td>
                        <td><?php echo $log_data['hostname']; ?></td>
                        <td><?php echo $log_data['pageviews_s']; ?></td>
                        <td><?php echo $log_data['pageviews_m']; ?></td>
                        <td><?php echo $log_data['pageviews_h']; ?></td>
                        <td><?php echo $log_data['pageviews_d']; ?></td>
                        <td><?php echo $log_data['flag_js_cookie']; ?></td>
                        <td><?php echo $log_data['flag_multi_session']; ?></td>
                        <td><?php echo $log_data['flag_empty_referer']; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $log_data['last_time']); ?></td>
                    </tr>
				<?php endforeach; ?>
			</tbody>   
		</table>
    </div>
    <div class="so-timezone">
        <?php printf(__('Current data circle started from %s.', 'wp-shieldon' ), date('Y-m-d H:i:s', $last_reset_time) ); ?><br />
        Timezone: <?php echo $timezone; ?>
    </div>
</div>

<script>

	$(function() {
		$('#so-datalog').DataTable({
			'pageLength': 25,
			'initComplete': function(settings, json ) {
				$('#so-table-loading').hide();
				$('#so-table-container').fadeOut(800);
				$('#so-table-container').fadeIn(800);
			}
		});
	});

</script>