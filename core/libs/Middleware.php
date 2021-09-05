<?php

interface Middleware
{
    /**
     * @param array $params
     */
    function initParameters($params);
    /**
     * @return boolean
     */
    function authorize(Request $request);

    /**
     * @return boolean
     */
    function handle(Request $request);
}