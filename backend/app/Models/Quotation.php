<?php

namespace App\Models;

use App\Support\QuotationAmounts;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'lead_id',
    'previous_quotation_id',
    'revision',
    'status',
    'currency',
    'customer_name',
    'customer_company',
    'customer_contact',
    'customer_email',
    'customer_address',
    'subtotal',
    'total',
    'created_by_user_id',
    'sent_by_user_id',
    'sent_to_email',
    'sent_at',
])]
class Quotation extends Model
{
    use HasFactory;

    protected $appends = ['quote_number'];

    protected function casts(): array
    {
        return [
            'revision' => 'integer',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'sent_at' => 'datetime',
        ];
    }

    public function getQuoteNumberAttribute(): string
    {
        return sprintf('QT-%05d-%03d', $this->lead_id, $this->revision);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function previousQuotation(): BelongsTo
    {
        return $this->belongsTo(self::class, 'previous_quotation_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(self::class, 'previous_quotation_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }

    /**
     * @param  array<int, array{product_name: string, details?: ?string, quantity: int, unit_price: string|int|float}>  $items
     */
    public function replaceItems(array $items, QuotationAmounts $amounts): void
    {
        $calculation = $amounts->calculate($items);
        $this->items()->delete();

        $this->items()->createMany(array_map(
            fn (array $item, int $index): array => [
                'product_name' => $item['product_name'],
                'details' => $item['details'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $calculation['items'][$index]['unit_price'],
                'line_total' => $calculation['items'][$index]['line_total'],
                'sort_order' => $index,
            ],
            $items,
            array_keys($items),
        ));

        $this->forceFill([
            'subtotal' => $calculation['total'],
            'total' => $calculation['total'],
        ])->save();
    }
}
