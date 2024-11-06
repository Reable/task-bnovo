<?php 

/**
 * @var string $page
 * @var array $data
 */
function view($page, $data = []): void
{
    extract($data);

    require_once "views/" . $page. ".php";
}