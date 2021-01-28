<?php

use App\Database\DatabaseFetcherFactory;
use App\Enum\SourceEnum;
use App\Repository\VerbRepository;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://cooljugator.com/fr/list/all'
]);

$response = curl_exec($curl);
$ul = explode('<ul', $response)[1];
$itemsStartSplit = explode('<li class="item"', $ul);

$verbs = [];

foreach ($itemsStartSplit as $index => $itemStartSplit) {

    if ($index === 0) {
        continue;
    }

    $splitOnId = explode(' id="', $itemStartSplit);

    if (count($splitOnId) === 2) {
        $tmpExplodeDelimiter = '">';
        $tmpExplode = explode($tmpExplodeDelimiter, $splitOnId[1]);
        array_shift($tmpExplode);
        $itemStartSplit2 = implode($tmpExplodeDelimiter, $tmpExplode);
    } else {
        $itemStartSplit2 = substr($itemStartSplit, 1);
    }

    $itemEndSplit = explode('</li>', $itemStartSplit2)[0];

    $verbs[] = explode('</a>', explode('">', $itemEndSplit)[1])[0];
}

$repository = new VerbRepository(DatabaseFetcherFactory::make());
$repository->addNewVerbsIfMissing($verbs, SourceEnum::COOLJUGATOR);
