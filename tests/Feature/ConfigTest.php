<?php

it('has config', function () {
    expect(config('profile'))->not->toBeEmpty();
});
