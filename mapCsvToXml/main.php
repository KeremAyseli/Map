<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL,"US");

require_once __DIR__ . './constants.php';

//Read file from TMP_PATH.TMP_PATH=__DIR__."./storage/tmp/.
$filesPath = glob(TMP_PATH . '*.csv');

//Check filespath is false or empty(0) array. When error is false ,empty array when no file matched.
if ($filesPath == false || count($filesPath) == 0) {
    die("No files found");
}

foreach ($filesPath as $filePath) {

    //get name of file
    $fileName = basename($filePath, ".csv");

    //Read data from filePath 
    $rawData = file_get_contents($filePath);

    //Explode file to every line.
    $rawlines = explode("\n", $rawData);

    //split every csv line by ; and assignment into splittedLine.
    $splittedLine =  array_map(
        function ($lineFromCSV) {
            return explode(";", $lineFromCSV);
        },
        $rawlines
    );

    //Get first line of csv file. First line is include elements name of header.
    $xmlHeaderTag = array_shift($splittedLine);

    //Get second line of csv file. Second line is include text of elements.
    $xmlHeaderText = array_shift($splittedLine);

    //Check if elements and elements text count is equal
    if (count($xmlHeaderTag) > count($xmlHeaderText) || count($xmlHeaderTag) < count($xmlHeaderText)) {
        $errXml = new SimpleXMLElement("<error>Header tags and text count is not equal</error>");
        $errXml->asXML(ERR_PATH . "/" . $fileName . " " . date("Y-m-d H.i.s") . ".xml");
        continue;
    }

    $xmlHeaderElement = array_combine($xmlHeaderTag, $xmlHeaderText);

    //dateCreated and dateSend element date format change to YmdHis To Y-m-d H:i:s
    $xmlHeaderElement["dateCreated"] = DateTime::createFromFormat("YmdHis", $xmlHeaderElement["dateCreated"])->format("Y-m-d H:i:s");
    $xmlHeaderElement["dateSend"] = DateTime::createFromFormat("YmdHis", $xmlHeaderElement["dateSend"])->format("Y-m-d H:i:s");

    //Change array name for better reading.
    $productRows = $splittedLine;

    //Get third line of csv file. Third line is include elements name of Product line.
    $productTag = array_shift($productRows);


    //Combine every productTag and productRows(after 4.csv line evert line is a product info) as key and value.
    //Check productTag and every product text count is equal.
    foreach ($productRows as $productRow) {

        if (count($productTag) > count($productRow) || count($productTag) < count($productRow)) {
            $errXml = new SimpleXMLElement("<error>Product tags and text count is not equal</error>");
            $errXml->asXML(ERR_PATH . "/" . $fileName . " " . date("Y-m-d H.i.s") . ".xml");
            continue;
        }

        $xmlProductsElement[] = array_combine($productTag, $productRow);
    }

    //Replace itemDescription element text it's Turkish letter with it's corresponding letter in english.
    //deliveryDateLatest element text date format change to 01Jun2022 to 01012022.
    echo mb_detect_encoding($xmlProductsElement[0]["itemDescription"]);
    var_dump (mb_convert_encoding($xmlProductsElement[0]["itemDescription"],"UTF-8","ISO-8859-1" ));
    for ($i = 0; $i < count($xmlProductsElement); $i++) {
        // $xmlProductsElement[$i]["itemDescription"] = replaceTrChars($xmlProductsElement[$i]["itemDescription"]);
        $xmlProductsElement[$i]["itemDescription"] =str_replace("\"","", iconv("UTF-8","ASCII//TRANSLIT",$xmlProductsElement[$i]["itemDescription"]));
        $xmlProductsElement[$i]["deliveryDateLatest"] = date("dmy", strtotime($xmlProductsElement[$i]["deliveryDateLatest"]));
    }

    //Filter xmlProductsElement by itemCode element is empty or not and price element is true decimal formant check.
    //preg_match explain: If price is true decimal format,preg_match return 1 otherwise 0.(true format=0,50,false format=,50)
    //^=Finds a match as the beginning of a string as in
    //\d=Find a digit 
    //+=Matches any string that contains at least one n(\d+) 
    //$=Finds a match at the end of the string as in
    //?=Matches any string that contains zero or one occurrences of n((,\d+)?)
    $xmlProductsElement = array_filter(
        $xmlProductsElement,
        function ($xmlproductItem) {
            if ($xmlproductItem["itemCode"] != null && preg_match("~^\d+(,\d+)?$~", $xmlproductItem["price"]) != 0) {
                return $xmlproductItem;
            }
        }
    );

    //Create a XML documnet between order element.
    $xml = new SimpleXMLElement('<order></order>');

    //add header element to add order element
    $xml->addChild('header');

    //Parse xmlheader to headerTags and headerText.
    //Then add to xml headerTags as element tag name and headerText as element text.
    foreach ($xmlHeaderElement as $headerTags => $headerText) {
        $xml->header->addChild($headerTags, $headerText);
    }

    //Add lines element to order elements
    $xml->addChild('lines');

    //Parse xmlProductsElement as product and then add a line to lines elements
    //then parse product as productTag and productText.
    foreach ($xmlProductsElement as $product) {
        $line = $xml->lines->addChild('line');
        foreach ($product as $productTag => $productText) {
            $line->addChild($productTag, $productText);
        }
    }

    //XML document save //!Storage/out/
    $xml->asXML(OUT_PATH . "/" . $fileName . ".xml");
}


function replaceTrChars($str)
{
    static $turkishLetter = [
        "Ãœ", "Å", "Ä", "Ã‡", "Ä°", "Ã–", "Ã¼", "ÅŸ", "Ã§", "Ä±", "Ã¶", "ÄŸ",
        "Ü", "Ş", "Ğ", "Ç", "İ", "Ö", "ü", "ş", "ç", "ı", "ö", "ğ",
        "%u015F", "%E7", "%FC", "%u0131", "%F6", "%u015E", "%C7", "%DC", "%D6",
        "%u0130", "%u011F", "%u011E"
    ];
    static $englishLetter = [
        'U', "S", "G", "C", "I", "O", "u", "s", "c", "i", "o", "g",
        "U", "S", "G", "C", "I", "O", "u", "s", "c", "i", "o", "g",
        "s", "c", "u", "i", "o", "S", "C", "U", "O", "I", "g", "G"
    ];

    return str_replace($turkishLetter, $englishLetter, $str);
}
