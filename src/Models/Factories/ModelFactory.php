<?php

namespace App\Models\Factories;

use App\Models\AbstractModel;

interface ModelFactory
{
    /**
     * @return AbstractModel
     */
    public function createFromArray(array $modelsAsArray);
}