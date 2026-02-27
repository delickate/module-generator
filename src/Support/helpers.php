<?php

if (! function_exists('module_path')) {
    function module_path(string $module, string $path = '')
    {
        $modulePath = base_path('Modules/' . $module);

        return $path
            ? $modulePath . '/' . ltrim($path, '/')
            : $modulePath;
    }
}


function module_config($module, $key = null)
{
    $config = config(strtolower($module));

    return $key ? data_get($config, $key) : $config;
}


function module_asset($module, $asset)
{
    return asset('modules/' . strtolower($module) . '/' . $asset);
}

