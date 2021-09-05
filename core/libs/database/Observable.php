<?php


interface Observable
{
    function inserted($model);
    function updated($model);
    function deleted($model);

}