<?php

if (function_exists('rename')) {

    function rename_fn($old, $new)
    {
        $a = rename($old, $new);
        $b = 'failed';
        if ($a) {
            $b = 'success';
        }
        echo "Renaming $old to $new was $b";
    }

    rename_fn('include', 'old_include');
    rename_fn('include_once', 'old_include_once');

    rename_fn('require', 'old_require');
    rename_fn('require_once', 'old_require_once');


    function new_include($name)
    {
        echo $name;
        /** @noinspection PhpUndefinedFunctionInspection */
        return old_include($name);
    }

    function new_include_once($name)
    {
        echo $name;
        /** @noinspection PhpUndefinedFunctionInspection */
        return old_include_once($name);
    }

    function new_require($name)
    {
        echo $name;
        /** @noinspection PhpUndefinedFunctionInspection */
        return old_require($name);
    }

    function new_require_once($name)
    {
        echo $name;
        /** @noinspection PhpUndefinedFunctionInspection */
        return old_require_once($name);
    }

    rename_fn('new_include', 'include');
    rename_fn('new_include_once', 'include_once');

    rename_fn('new_require', 'require');
    rename_fn('new_require_once', 'require_once');
}