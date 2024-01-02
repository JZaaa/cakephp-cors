<?php

namespace Cors\Error;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use CorsMiddleware;

function get_dynamic_parent() {
    $baseExceptionRenderer = Configure::read('Error.baseExceptionRenderer');
    return !empty($baseExceptionRenderer) ? $baseExceptionRenderer : 'Cake\Error\Renderer\WebExceptionRenderer';// return what you need
}
class_alias(get_dynamic_parent(), 'Cors\Error\BaseExceptionRenderer');

class AppExceptionRenderer extends BaseExceptionRenderer
{

    /**
     * Returns the current controller.
     *
     * @return \Cake\Controller\Controller
     */
    protected function _getController(): Controller
    {
        $controller = parent::_getController();
        $cors = new CorsMiddleware();
        $controller->response = $cors->addHeaders(
            $controller->getRequest(),
            $controller->getResponse()
        );
        return $controller;
    }


}
