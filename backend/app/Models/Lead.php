<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'customer_name',
    'customer_company',
    'customer_contact',
    'customer_email',
    'customer_address',
    'deadline',
    'pic_user_id',
    'created_by_user_id',
])]
class Lead extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(LeadItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @param  array<int, array{product_name: string, details?: ?string, quantity: int}>  $items
     */
    public function replaceItems(array $items): void
    {
        $this->items()->delete();
        $this->items()->createMany(array_map(
            fn (array $item, int $index): array => [
                'product_name' => $item['product_name'],
                'details' => $item['details'] ?? null,
                'quantity' => $item['quantity'],
                'sort_order' => $index,
            ],
            $items,
            array_keys($items),
        ));
    }
}
