<?php

namespace Router;

class Router
{
    private $requestUri;
    private $controllers = [];
    private $routes = [];
    private $result = false;

    public function setUri($requestUri) //store user supplied URI
    {
        $this->requestUri = $requestUri;
    }

    public function addController($controllerName, $controllerObject) //add controllers to an array
    {
        $this->controllers[$controllerName] = $controllerObject;
    }

    public function setRoute ($url, $controller) //maps the routes in an associative array
    {
        $this->routes[$url] = $controller;
    }

    public function run() //searches for a valid route, if found, executes the command, if not, displays error to screen
    {
        foreach ($this->routes as $key => $value) { //iterate over each possible route
            if ($key === $this->requestUri) { //check to see if there is a match for provided URL
                $this->controllers[$value]->execute(); //execute the relevant transaction method
                $this->result = true; //stops an error being returned
            }
        }

        if ($this->result !== true) { //if route wasn't found
            echo "A valid route could not be found, please enter a valid URL";
        }
    }
}