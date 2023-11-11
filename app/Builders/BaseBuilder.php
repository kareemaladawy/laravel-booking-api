<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class BaseBuilder extends Builder
{
    public function deactivate()
    {
        $this->model->update([
            'active' => false
        ]);
    }

    public function activate()
    {
        $this->model->update([
            'active' => true
        ]);
    }
}
