<?php

namespace System\Contracts;

interface IRule {
    public function isValid($input = null) : bool;
    public function getErrorText() : string;
}