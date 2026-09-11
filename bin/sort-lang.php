<?php

/**
 * Sorts every language file by its own collation. The option order is the file order,
 * so run this after adding or renaming a country.
 *
 * php bin/sort-lang.php
 */
if (! extension_loaded('intl')) {
    exit("ext-intl is required.\n");
}

foreach (glob(__DIR__.'/../resources/lang/*', GLOB_ONLYDIR) as $dir) {
    $locale = basename($dir);
    $countries = require $dir.'/countries.php';
    $collator = new Collator($locale);

    uasort($countries, fn (string $a, string $b): int => $collator->compare($a, $b));

    $php = "<?php\n\nreturn [\n";

    foreach ($countries as $code => $name) {
        $php .= '    '.var_export((string) $code, true).' => '.var_export($name, true).",\n";
    }

    file_put_contents($dir.'/countries.php', $php."];\n");

    echo str_pad($locale, 6), count($countries), " countries\n";
}
