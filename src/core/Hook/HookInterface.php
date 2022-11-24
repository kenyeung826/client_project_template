<?php

namespace Core\Hook;

interface HookInterface
{
    /**
     * @param null $argument
     *
     * @return mixed
     */
    public function handle($argument = null);
}
