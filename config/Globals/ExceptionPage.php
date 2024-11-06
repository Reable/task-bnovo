<?php

function exception_page($message)
{
    return view("errors/exception", [
        "message" => $message
    ]);
}