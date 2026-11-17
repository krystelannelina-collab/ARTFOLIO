<?php

it('sends guests from the root address to the public home page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('home'));
});
