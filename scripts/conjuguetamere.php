<?php

$curl = curl_init('http://www.conjuguetamere.com/verbes-1er-groupe');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

$buttonCount = count(explode('class="aPagination"', $response));
$lastPage = $buttonCount > 1 ? $buttonCount - 2 : 1;

$verbs = [];

for ($page = 1; $page <= $lastPage; $page++) {

    $curl = curl_init('http://www.conjuguetamere.com/verbes-1er-groupe?page=' . $page);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);

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
