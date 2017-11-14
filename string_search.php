<?php
$errors = [];
$found_strings = [];
if (isset($_POST['upload']))
{
	if (empty($_POST['string-in']))
	{
		$errors[] = 'Please input a search string';
	} else {
		$string_in = strtolower($_POST['string-in']);
		sort_string($string_in);
		$repeated = preg_match('/(.)\1{1,}/i', $string_in);
		if ($repeated)
		{
			$errors[] = 'Search strings may not have repeated characters';
		}
		if (!preg_match('/^(\d|[a-z]){0,}$/i', $string_in))
		{
			$errors[] = 'Search can be alphanumeric only';
		}
	}

	# Assuming the string list matches the required rules.
	if (empty($_FILES['upload']['tmp_name'])) $errors[] = 'Please select a (valid) file to upload';


	if (empty($errors))
	{
		$file = file_get_contents($_FILES["upload"]["tmp_name"]);

		$string_list = explode(',', $file);

		$found = false;
		foreach ($string_list as $test_string)
		{
			$sample_string = strtolower($test_string);
			sort_string($sample_string);
			if ($sample_string == $string_in)
			{
				$found = true;
				$found_strings[] = $test_string;
			}
		}

		if (!$found)
		{
			$errors[] = 'No search string found';
		}
	}

}

function sort_string(&$string_in)
{
	$string_array =  str_split($string_in);
	sort($string_array);
	$string_in = implode('',$string_array);
}
?>

<h1>String Search</h1>
<p>Upload a file, and enter a search string, and it will locate all equivalent strings in the file</p>
<form action="" enctype="multipart/form-data" method="POST">
	<label for="string-in">Search String:</label>
	<input type="text" name="string-in" id="string-in"/><br /><br />

	<label for="upload">Input File:</label>
	<input type="file" name="upload" id="upload"/><br /><br />

	<input type="submit" name="upload" value="Search String"/> <br /><br />

	<div style="color: darkred">
<?php
	foreach ( $errors as $error) {
		echo "<div>$error</div><br />";
	}
?>
	</div>
	<?php
	if (!empty($found_strings))
	{
		?>
		<h2>Original Search String: <strong><?php echo $_POST['string-in']; ?></strong></h2>
		<div style="color: green">
			<?php
			foreach ( $found_strings as $string) {
				echo "<div>$string</div><br />";
			}
			?>
		</div>
		<?php
	}
	?>

</form>