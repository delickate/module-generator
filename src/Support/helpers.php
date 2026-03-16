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

function getModuleStatuses()
{
    $file = config('modules.statuses_file');

    if (!file_exists($file)) {
        return [];
    }

    return json_decode(file_get_contents($file), true);
}

function module_enabled($module)
{
    $file = config('modules.statuses_file');

    if (!file_exists($file)) {
        return true;
    }

    $statuses = json_decode(file_get_contents($file), true);

    return $statuses[$module] ?? true;
}

function set_module_status($module, $status = true)
{
    $file = config('modules.statuses_file') ?: base_path('modules_statuses.json');

    if (!$file) {
        $file = base_path('modules_statuses.json');
    }

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
    }

    $statuses = json_decode(file_get_contents($file), true) ?? [];

    $statuses[$module] = $status;

    file_put_contents($file, json_encode($statuses, JSON_PRETTY_PRINT));
}
