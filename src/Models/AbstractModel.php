<?php

namespace App\Models;

abstract class AbstractModel
{
    //TODO: remove nullable type after repository is ready
    abstract public function getId(): ?int;
}