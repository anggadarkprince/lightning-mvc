<?php

namespace Core\Base;

use Core\Error;
use Exception;

class View
{
    public static $layout = 'base';

    /**
     * Render default php or template.
     *
     * @param $view
     * @param array $args
     * @param bool $return
     * @return string|void
     */
    public static function render($view, $args = [], $return = false)
    {
        $basePath = BASE_PATH . '/app/Views/';

        $viewFile = self::resolveViewFile($basePath . $view);

        if (is_readable($viewFile)) {
            $args['__lightning_content'] = $view;
            $args['__lightning_content_data'] = $args;
            extract($args, EXTR_SKIP);

            if ($return) {
                ob_start();
                require_once $viewFile;
                $renderedView = ob_get_contents();
                ob_end_clean();

                return $renderedView;
            } else {
                require_once self::resolveViewFile($basePath . self::$layout);
            }
        } else {
            echo $viewFile;
            Error::exceptionHandler(new Exception("{$viewFile} not found."));
        }
    }

    /**
     * Render partial of view.
     *
     * @param $view
     * @param array $args
     */
    public static function renderPartial($view, $args = [])
    {
        $basePath = BASE_PATH . '/app/Views/';

        $viewFile = self::resolveViewFile($basePath . $view);

        if (is_readable($viewFile)) {
            extract($args, EXTR_SKIP);
            require_once $viewFile;
        } else {
            Error::exceptionHandler(new Exception("{$viewFile} not found."));
        }
    }

    /**
     * Resolve file view, guess extension php or html.
     *
     * @param $viewFile
     * @return string
     */
    private static function resolveViewFile($viewFile)
    {
        if (!file_exists($viewFile)) {
            if (file_exists($viewFile . '.php')) {
                $viewFile .= '.php';
            } else if (file_exists($viewFile . '.html')) {
                $viewFile .= '.html';
            }
        }
        return $viewFile;
    }
}