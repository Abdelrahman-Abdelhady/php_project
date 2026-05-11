<?php

class ErrorController extends Controller
{
    public function error403()
    {
        $this->view("errors/error403");
    }

    public function error404()
    {
        $this->view("errors/error404");
    }
}