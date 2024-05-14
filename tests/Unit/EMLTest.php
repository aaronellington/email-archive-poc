<?php

use App\EML;

test('EML test', function () {
    $exampleFilePath = __DIR__.'/../TestFiles/script_injection.eml';
    $resource = fopen($exampleFilePath, 'r');
    $eml = EML::parse($resource);

    expect($eml->subject)->toBe('Hello there!');
    expect($eml->to)->toBe('aaron@example.com');
    expect($eml->from)->toBe('kristel@example.com');
    expect(str_contains($eml->html, 'Friday'))->toBe(true);
    expect(count($eml->attachments))->toBe(0);
});
