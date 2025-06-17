<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Rating;

class RatingForm extends Component
{
    public Order $order;
    public $rating_value = 0;
    public $comment = '';
    public $hasRated = false;

    public function mount(Order $order)
    {
        $this->order = $order;

        if ($this->order->rating) {
            $this->hasRated = true;
            $this->rating_value = $this->order->rating->rating_value;
            $this->comment = $this->order->rating->comment;
        }
    }

    public function setRating($value)
    {
        if (!$this->hasRated) {
            $this->rating_value = $value;
        }
    }

    public function submitRating()
    {
        $this->validate([
            'rating_value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($this->order->rating) {
            session()->flash('error', 'Anda sudah memberikan rating.');
            return;
        }

        Rating::create([
            'order_id'     => $this->order->id,
            'rating_value' => $this->rating_value,
            'comment'      => $this->comment,
        ]);

        $this->hasRated = true;
        session()->flash('message', 'Terima kasih atas ulasan Anda!');
    }

    public function render()
    {
        return view('livewire.rating-form')->layout('layouts.guest');
    }
}
