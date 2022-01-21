<?php

error_reporting(E_ALL);

require_once __DIR__ . './constants.php';

$files = glob(TMP_PATH . '*.csv');

if ($files == false || count($files) == 0) {
    die("No files found");
}

foreach ($files as $file) {

    $fileName = basename($file, ".csv");
    $rawData = file_get_contents($file);
    $rawlines = explode("\n", $rawData);

    $splittedLine =  array_map(
        function ($lineFromCSV) {
            return explode(";", $lineFromCSV);
        },
        $rawlines
    );

    $xmlHeaderElement = array_shift($splittedLine);
    $xmlHeaderInfo = array_shift($splittedLine);
    $xmlHeader = array_combine($xmlHeaderElement, $xmlHeaderInfo);

    $productRowsInfo = $splittedLine;
    $productElement = array_shift($productRowsInfo);

    foreach ($productRowsInfo as $productRowInfo) {
        $xmlproductItems[] = array_combine($productElement, $productRowInfo);
    }

    $xml = new SimpleXMLElement('<order></order>');
    $xml->addChild('header');

    foreach ($xmlHeader as $headerIndex => $headerInfo) {
        $xml->header->addChild($headerIndex, $headerInfo);
    }

    $xml->addChild('lines');

    foreach ($xmlproductItems as $productIndex => $product) {
        $line = $xml->lines->addChild('line');
        foreach ($product as $productElement => $productInfo) {
            $line->addChild($productElement, $productInfo);
        }
    }

    //XML document save //!Storage/out/
    $xml->asXML(OUT_PATH . "/" . $fileName . ".xml");
}
