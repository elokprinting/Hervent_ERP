<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lead_id', 'product_name', 'details', 'quantity', 'sort_order'])]
class LeadItem extends Model
{
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
