<?php
class Connection extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->connectionModel = $this->model('Connection');
    }
}
