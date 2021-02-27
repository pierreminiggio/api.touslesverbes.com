<?php

$groups = [
    1 => 'http://www.conjuguetamere.com/verbes-1er-groupe',
    2 => 'http://www.conjuguetamere.com/verbes-2eme-groupe',
    3 => 'http://www.conjuguetamere.com/verbes-3eme-groupe'
];

foreach ($groups as $groupId => $sourceUrl) {
    $response = getHtml($sourceUrl);

    $buttonCount = count(explode('class="aPagination"', $response));
    $lastPage = $buttonCount > 1 ? $buttonCount - 2 : 1;

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

    var_dump($verbs);
}

function getHtml(string $url, ?int $page = null): string
{
    $curl = curl_init($url . ($page ? '?page=' . $page : ''));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
