<?php
namespace Core\Base;

use Exception;

abstract class Controller
{
    protected $routeParams = [];

    /**
     * Controller constructor.
     * @param $routeParams
     */
    public function __construct($routeParams)
    {
        $this->routeParams = $routeParams;
    }

    /**
     * Fallback if method not found.
     *
     * @param $name
     * @param $arguments
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $method = $name . 'Action';

        if(method_exists($this, $method)) {
            if($this->before() !== false) {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        } else {
            throw new Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before()
    {

    }

    protected function after()
    {

    }

}