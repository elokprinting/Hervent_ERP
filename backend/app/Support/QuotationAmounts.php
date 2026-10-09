<?php

namespace App\Support;

use InvalidArgumentException;
use OverflowException;

class QuotationAmounts
{
    private const MAX_CENTS = 999_999_999_999_999;

    /**
     * @param  array<int, array{quantity: int, unit_price: string|int|float}>  $items
     * @return array{items: array<int, array{unit_price: string, line_total: string}>, total: string}
     */
    public function calculate(array $items): array
    {
        $totalCents = 0;
        $calculatedItems = [];

        foreach ($items as $item) {
            $unitPriceCents = $this->toCents($item['unit_price']);
            $quantity = $item['quantity'];

            if ($unitPriceCents > intdiv(self::MAX_CENTS, $quantity)) {
                throw new OverflowException('Quotation line total exceeds the supported amount.');
            }

            $lineTotalCents = $unitPriceCents * $quantity;

            if ($totalCents > self::MAX_CENTS - $lineTotalCents) {
                throw new OverflowException('Quotation total exceeds the supported amount.');
            }

            $totalCents += $lineTotalCents;
            $calculatedItems[] = [
                'unit_price' => $this->formatCents($unitPriceCents),
                'line_total' => $this->formatCents($lineTotalCents),
            ];
        }

        $total = $this->formatCents($totalCents);

        return [
            'items' => $calculatedItems,
            'total' => $total,
        ];
    }

    private function toCents(string|int|float $amount): int
    {
        $amount = (string) $amount;

        if (! preg_match('/\A(\d+)(?:\.(\d{1,2}))?\z/', $amount, $matches)) {
            throw new InvalidArgumentException('Quotation prices must have at most two decimal places.');
        }

        $wholeAmount = (int) $matches[1];

        if ($wholeAmount > intdiv(self::MAX_CENTS, 100)) {
            throw new OverflowException('Quotation price exceeds the supported amount.');
        }

        $fractionalAmount = (int) str_pad($matches[2] ?? '', 2, '0');
        $cents = ($wholeAmount * 100) + $fractionalAmount;

        if ($cents > self::MAX_CENTS) {
            throw new OverflowException('Quotation price exceeds the supported amount.');
        }

        return $cents;
    }

    private function formatCents(int $cents): string
    {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}
