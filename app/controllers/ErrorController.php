<?php

class ErrorController extends Controller
{
    public function error403()
    {
        $this->view("errors/error403");
    }
}
