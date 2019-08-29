<?php

/**
 * @param string $route_name : ルート名
 * @param array $route_params : ルートパラメータ
 * @param array $query_params : クエリパラメータ
 * @return void
 */
function route_with_query($route_name, $route_params = [], $query_params = [])
{
    // 固定パラメータがあった場合、固定ルートを生成する
    $static_route = (empty($route_params)) ? route($route_name) : route($route_name, $route_params);
    // クエリパラメータがあった場合、クエリストリング付きのルートを生成する
    return (empty($query_params)) ? $static_route : $static_route . '?' . http_build_query($query_params);
}
