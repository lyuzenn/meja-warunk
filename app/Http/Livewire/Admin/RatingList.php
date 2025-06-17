<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rating;

class RatingList extends Component
{
    use WithPagination;

    public function render()
    {
        $ratings = Rating::with('order.table')->latest()->paginate(10);

        return view('livewire.admin.rating-list', [
            'ratings' => $ratings
        ])->layout('layouts.app');
    }
}
