<?php
function shortcode_ams_data_chart($atts)
{
	$a = shortcode_atts(array(
		'city' => 'Benicia' // (string) Benicia | Sunnyvale
	), $atts);

	ob_start();
	$endpoint = 'https://amslivedataapi.azurewebsites.net/Thm/Readings';
	try
	{
		$json = file_get_contents($endpoint);
		if($json === false)
		{
			throw new Exception('Error fetching the JSON feed.');
		}
		$data = json_decode($json, true);
		if($data === null)
		{
			throw new Exception('Error decoding the JSON feed.');
		}
		$jsData = []; // Store formatted data
		foreach($data as $region)
		{
			foreach($region['cities'] as $city)
			{
				if($city['name'] === $a['city'])
				{
					foreach($city['readings'] as $reading)
					{
						//echo '<pre>'; print_r($reading); echo '</pre>';
						$timestamp = $reading['timeStamp']; // Keep as ISO 8601 string
						$formattedDate = date('M d, Y \a\t H:i', strtotime($timestamp));

						$value = $reading['tthmConc'];

						$sampleType = $reading['sampleType'];
						if($sampleType === 'online'): $formattedType = 'TTHM'; elseif($sampleType === 'thm_fp'): $formattedType = 'THM-FP'; endif;

						//$tooltip = '<div style="padding:10px;"><p style="margin:0;"><strong>'.$formattedDate.'</strong><br>'.number_format($value, 1).' '.$formattedType.'</p></div>';
						$tooltip = $formattedDate.' '.number_format($value, 1).' '.$formattedType;
						//$tooltip = htmlspecialchars_decode($tooltip, ENT_QUOTES);
						$jsData[] = "[new Date('$timestamp'), $value, '$tooltip']";
					}
					break; // Stop processing other cities
				}
			}
		}
		// Convert array to a JavaScript-friendly format
		$jsDataString = implode(",\n", $jsData);
	}
	catch(Exception $e)
	{
		$jsDataString = "console.error('".$e->getMessage()."');";
	}
	$chartID = uniqid(rand(), true);
	?>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			google.charts.load('current', {packages: ['corechart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart()
			{
				var data = new google.visualization.DataTable();
				data.addColumn('datetime', 'Timestamp');
				data.addColumn('number', 'Value');
				//data.addColumn({ type:'string', role: 'tooltip', 'p': {'html': true } });
				data.addColumn({ type:'string', role: 'tooltip' });

				data.addRows([
					<?php echo $jsDataString;?>
				]);

				var options = {
					title: 'Real-time THM data, <?php echo $a['city'];?>, California',
					//tooltip: { isHtml: true },
					curveType: 'function',
					legend: { position: 'left' },
					hAxis: { title: null, format: 'MMM dd, HH:mm' },
					vAxis: {
						title: 'THM Concentration (ppb)',
						minValue: 0,
						maxValue: 100,
						gridlines: { color: '#E0E0E0' },
						baseline: 80, // Sets the threshold visually
						baselineColor: 'red' // Colors the threshold line
					},
					chartArea: { width: '85%', height: '70%' },
					pointSize: 5,
					colors: [getComputedStyle(document.documentElement).getPropertyValue('--amsBrandBlue').trim()],
					animation: {
						startup: true, // Enables animation when the chart first loads
						duration: 1000, // Animation duration in milliseconds
						easing: 'out' // Easing effect (options: 'linear', 'in', 'out', 'inAndOut')
					}
				};

				var chart = new google.visualization.LineChart(document.querySelector('[data-chart-id="<?php echo $chartID;?>"]'));
				chart.draw(data, options);
				window.addEventListener('resize', drawChart);
			}
		</script>
		<div id="amsDataChart" data-chart-id="<?php echo $chartID;?>"></div>
	<?php
	$chartID = null;
	return ob_get_clean();
}
add_shortcode('ams_data_chart', 'shortcode_ams_data_chart');
