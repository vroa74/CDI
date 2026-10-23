<?php

it('redirects the public landing page to login', function () {
    $this->get('/')->assertRedirect(route('login', absolute: false));
});
