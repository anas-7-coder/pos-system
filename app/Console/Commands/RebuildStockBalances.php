<?php

namespace App\Console\Commands;

use App\Models\StockBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildStockBalances extends Command
{
    protected $signature = 'inventory:rebuild-balances';

    protected $description = 'Rebuild stock balances from stock movements';

    public function handle(): int
    {
        $this->info('Rebuilding stock balances...');
        StockBalance::query()->delete();

        $query = DB::table('stock_movements')
            ->select('warehouse_id', 'product_id', 'product_variant_id')
            ->selectRaw('SUM(quantity) as quantity')
            ->groupBy('warehouse_id', 'product_id', 'product_variant_id')
            ->orderBy('warehouse_id')
            ->orderBy('product_id');

        $rows = [];

        foreach ($query->cursor() as $row) {

            $variantId = $row->product_variant_id !== null ? (int) $row->product_variant_id : null;

            $rows[] = [
                'warehouse_id' => (int) $row->warehouse_id,
                'product_id' => (int) $row->product_id,
                'product_variant_id' => $variantId,
                'balance_key' => $row->warehouse_id.':'.$row->product_id.':'.($variantId ?? 0),
                'quantity' => (float) $row->quantity,
                'reorder_level' => 0,
                'reserved_quantity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($rows) >= 500) {
                StockBalance::query()->insert($rows);
                $rows = [];
            }
        }

        if ($rows) {
            StockBalance::query()->insert($rows);
        }

        $this->info('Stock balances rebuilt successfully.');

        return self::SUCCESS;
    }
}
