<?php
namespace baohan\token;


interface HeaderUpdatable
{
    /**
     * @param string $key
     * @param string $val
     * @return bool
     */
    public function setHeader(string $key, string $val): bool;
}