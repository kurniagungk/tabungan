<?php

it('may welcome the user', function () {
    $page = visit('/');

    $page->assertPathIs('/');
});
