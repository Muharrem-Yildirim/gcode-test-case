<?php

use Illuminate\Support\Collection;

function xmlToCollection(SimpleXMLElement $xml, $path = ''): Collection
{
    $arrayData = json_decode(json_encode($xml), true);

    if ($path) {
        $arrayData = $arrayData[$path];
    }

    return collect($arrayData);
}
