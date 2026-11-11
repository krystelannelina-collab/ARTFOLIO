<?php

it('redirects guests to the Breeze login screen', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
