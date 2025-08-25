<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WasteReport;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing waste reports that don't have reference codes
        $reportsWithoutCodes = WasteReport::whereNull('reference_code')->get();
        
        foreach ($reportsWithoutCodes as $report) {
            $report->reference_code = $this->generateUniqueReferenceCode();
            $report->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear reference codes (if needed for rollback)
        // WasteReport::query()->update(['reference_code' => null]);
    }

    /**
     * Generate a unique reference code
     */
    private function generateUniqueReferenceCode()
    {
        do {
            $code = 'WR-' . strtoupper(substr(uniqid(), -8));
        } while (WasteReport::where('reference_code', $code)->exists());

        return $code;
    }
};
