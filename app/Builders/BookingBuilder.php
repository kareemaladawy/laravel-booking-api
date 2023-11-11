<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class BookingBuilder extends Builder
{
    public function cancel()
    {
        $this->model->delete();
    }
}
