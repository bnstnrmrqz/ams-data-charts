<?php
function shortcode_ams_data_chart($atts)
{
	$a = shortcode_atts(array(
		'type' => 'tthm', // (string) tthm | mg
		'city' => 'Benicia' // (string) Benicia | Sunnyvale | San Bernardino County (if[type]=mg)
	), $atts);

	ob_start();

	// Fetch data from API
	if($a['type'] === 'tthm')
	{
		$endpoint = 'https://amslivedataapi.azurewebsites.net/Thm/Readings';
		$chartTitle = 'THM Concentration (ppb)';
		$maxValue = 100;
		$baseline = 80;
		$baselineColor = 'red';
	}
	elseif($a['type'] === 'mg')
	{
		$endpoint = 'https://amslivedataapi.azurewebsites.net/Mg/Readings';
		$chartTitle = 'Cr(VI) Concentration (ppb)';
		$maxValue = 35;
		$baseline = 0;
		$baselineColor = 'transparent';
	}
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
		//echo '<pre>'; print_r($data); echo '</pre>';
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

						if($a['type'] === 'tthm')
						{
							$sampleType = $reading['sampleType'];
							if($sampleType === 'online')
							{
								$formattedType = 'TTHM';
							}
							elseif($sampleType === 'thm_fp')
							{
								$formattedType = 'THM-FP';
							}
							$value = $reading['tthmConc'];
							$tooltip = $formattedDate.' — '.number_format($value, 1).' '.$formattedType;
						}
						elseif($a['type'] === 'mg')
						{
							$value = $reading['concentration'];
							$tooltip = $formattedDate.' — '.number_format($value, 1).' ppb';
						}

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
				data.addColumn({ type:'string', role: 'tooltip' });

				data.addRows([
					<?php echo $jsDataString;?>
				]);

				var options = {
					curveType: 'function',
					legend: { position: 'left' },
					hAxis: { title: null, format: 'MMM dd YY, HH:mm' },
					vAxis: {
						title: '<?php echo $chartTitle;?>',
						minValue: 0,
						maxValue: <?php echo $maxValue;?>,
						gridlines: { color: '#E0E0E0' },
						baseline: <?php echo $baseline;?>, // Sets the threshold visually
						baselineColor: '<?php echo $baselineColor;?>' // Colors the threshold line
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
