<?php

class ApiView
{

  public function response($data, $code)
  {
    header("Content-Type: application/json");
    header("HTTP/1.1 " . $code . " " . $this->_requestStatus($code));
    echo json_encode($data);
  }

  private function _requestStatus($code)
  {
    $status = array(
      200 => "OK",
      201 => "Created",
      400 => "Bad Request",
      401 => "Unauthorized",
      404 => "Not Found",
      500 => "Internal Server Error"
    );
    return (isset($status[$code])) ? $status[$code] : $status[500];
  }
}
