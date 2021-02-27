<?php

use App\Database\DatabaseFetcherFactory;
use App\Enum\SourceEnum;
use App\Repository\VerbRepository;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$groups = [
    1 => 'http://www.conjuguetamere.com/verbes-1er-groupe',
    2 => 'http://www.conjuguetamere.com/verbes-2eme-groupe',
    3 => 'http://www.conjuguetamere.com/verbes-3eme-groupe'
];

$repository = new VerbRepository(DatabaseFetcherFactory::make());

foreach ($groups as $groupId => $sourceUrl) {
    $response = getHtml($sourceUrl);

    $lastPage = getLastPage($response);

    $verbs = [];

    for ($page = 1; $page <= $lastPage; $page++) {

        $response = getHtml($sourceUrl, $page);

        $liSplit = explode('<li style="">', $response);

        foreach ($liSplit as $liIndex => $liItem) {
            if ($liIndex === 0) {
                continue;
            }

            $link = explode('</li>', $liItem)[0];


            $explodedLink = explode('conjuguetamere.com/verbe/', $link);
            if (! isset($explodedLink[1])) {
                continue;
            }

            $verbs[] = explode('"', $explodedLink[1])[0];
        }
    }

    $repository->addNewVerbsAndGroupIfMissing($verbs, $groupId, SourceEnum::CONJUGUE_TA_MERE);
}

function getLastPage(string $html): int
{
    $buttonCount = getPaginationButtonCount($html);

    return $buttonCount > 1 ? $buttonCount - 2 : 1;
}

function getPaginationButtonCount(string $html): int
{
    return count(explode('class="aPagination"', $html));
}

function getHtml(string $url, ?int $page = null): string
{
    $curl = curl_init($url . ($page ? '?page=' . $page : ''));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
