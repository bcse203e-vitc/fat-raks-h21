<?php

function normalizeTextCompress($fileName) {

    if (!file_exists($fileName)) {
        die("Error: inp.txt not found!");
    }

    $content = file($fileName);
    $corrected = [];
    $corrections = 0;
    $punctLines = [];

    foreach ($content as $lineNumber => $line) {

        if (preg_match('/^[[:punct:]]+$/', trim($line))) {
            $punctLines[] = $lineNumber + 1;
        }

        $before = $line;

        $line = preg_replace('/[ \t]+/', ' ', $line);

        // Trim leading/trailing spaces
        $line = trim($line);

        if ($before !== $line) {
            $corrections++;
        }

        $corrected[] = $line;
    }

    $corrected = compressBlankLines($corrected);

    file_put_contents($fileName, implode(PHP_EOL, $corrected));

    echo "<h3>Lines containing only punctuation: ";
    if (!empty($punctLines)) echo implode(", ", $punctLines);
    else echo "None";
    echo "</h3>";

    echo "<h3>Total whitespace corrections: $corrections</h3>";

    echo "<h3>Corrected Output:</h3><pre>";
    echo implode(PHP_EOL, $corrected);
    echo "</pre>";
}

function compressBlankLines($lines) {
    $out = [];
    $blankSeen = false;

    foreach ($lines as $line) {
        if ($line === "") {
            if (!$blankSeen) {
                $out[] = "";
                $blankSeen = true;
            }
        } else {
            $out[] = $line;
            $blankSeen = false;
        }
    }
    return $out;
}


normalizeTextCompress("inp.txt");

?>
