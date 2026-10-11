<?php

namespace App\Models;

use Database\Factories\BatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Batch extends Model
{
    /** @use HasFactory<BatchFactory> */
    use HasFactory;

    public const TYPE_IN = 'in';

    public const TYPE_OUT = 'out';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'batch_number',
        'expiry_date',
        'barcode',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    /**
     * Assign a batch number and barcode before the batch is first stored.
     */
    protected static function booted(): void
    {
        static::creating(function (Batch $batch): void {
            $batch->batch_number ??= static::uniqueValue('LOTE-', 'batch_number');
            $batch->barcode ??= static::uniqueValue('AZ', 'barcode');
        });
    }

    /**
     * The product this batch belongs to.
     *
     * @return BelongsTo<Product, Batch>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The stock movements recorded for this batch.
     *
     * @return HasMany<InventoryLog>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * Apply a stock movement and record it in the inventory log.
     */
    public function adjustStock(string $type, int $quantity): void
    {
        $this->increment('quantity', $type === self::TYPE_IN ? $quantity : -$quantity);

        $this->logs()->create([
            'type' => $type,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Determine whether the batch can give up the given amount of units.
     */
    public function hasStockFor(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }

    /**
     * Build a batch number or barcode that is not already taken.
     */
    protected static function uniqueValue(string $prefix, string $column): string
    {
        do {
            $value = $prefix.strtoupper(Str::random(9));
        } while (static::query()->where($column, $value)->exists());

        return $value;
    }
}
