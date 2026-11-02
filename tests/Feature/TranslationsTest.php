<?php

use Illuminate\Support\Arr;

it('translates every key of the English file in every language', function () {
    $dir = __DIR__.'/../../resources/lang';
    $expected = array_keys(Arr::dot(require "{$dir}/en/default.php"));
    sort($expected);

    foreach (glob("{$dir}/*/default.php") as $file) {
        $keys = array_keys(Arr::dot(require $file));
        sort($keys);

        expect($keys)->toBe($expected, basename(dirname($file)).' is out of sync with en');
    }
});
