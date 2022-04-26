<?php

namespace command;

/**
 * @Command
 */
class Test
{
    /**
     * @exec(test)
     */
    public function test($params){
        var_dump($params);
    }
}