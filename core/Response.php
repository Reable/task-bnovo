<?php 

namespace Core;

class Response
{
    public function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);

        $data["statusCode"] = $status;

        return json_encode((object) $data);
    }

    public function error($error, $status = 500)
    {
        return $this->json(["error" => $error], $status);
    }
}