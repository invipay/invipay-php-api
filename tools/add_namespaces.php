<?php

// This tool will change every script in this API library by adding namespace directive to it.
// This will also cause CompatibilityLayer to kickin.

$namespace = 'InviPay';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/..', RecursiveDirectoryIterator::SKIP_DOTS));

foreach ($it as $file) {
	$file = realpath($file);
	if (pathinfo($file, PATHINFO_EXTENSION) == 'php' && pathinfo($file, PATHINFO_DIRNAME) != __DIR__)
	{
		echo $file."\r\n";
		$content = file_get_contents($file);
		$content = str_replace('<?php', "<?php \r\n\r\nnamespace " . $namespace . ";\r\n\r\n", $content);
		file_put_contents($file, $content);

	}
}


?>