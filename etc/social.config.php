<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


$adapterConfigs = array(
    'vk' => array(
        'client_id'     => '4984530',
        'client_secret' => 'I9JiGoLiZ7A079UhjnOr',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=vk'
    ),
    'odnoklassniki' => array(
        'client_id'     => '168635560',
        'client_secret' => 'C342554C028C0A76605C7C0F',
        'redirect_uri'  => 'http://localhost/auth?provider=odnoklassniki',
        'public_key'    => 'CBADCBMKABABABABA'
    ),
    'mailru' => array(
        'client_id'     => '770076',
        'client_secret' => '5b8f8906167229feccd2a7320dd6e140',
        'redirect_uri'  => 'http://localhost/auth/?provider=mailru'
    ),
    'yandex' => array(
        'client_id'     => '8a9cf6f8b9f24f5eba493cdac7a60097',
        'client_secret' => 'c79c0b3f98ac45b99d9a67216b251d4b',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=yandex'
    ),
    'google' => array(
        'client_id'     => '333193735318.apps.googleusercontent.com',
        'client_secret' => 'lZB3aW8gDjIEUG8I6WVcidt5',
        'redirect_uri'  => 'http://localhost/auth?provider=google'
    ),
    'facebook' => array(
        'client_id'     => '613418539539988',
        'client_secret' => '2deab137cc1d254d167720095ac0b386',
        'redirect_uri'  => 'http://localhost/auth?provider=facebook'
    )
);