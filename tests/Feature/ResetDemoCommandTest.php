<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResetDemoCommandTest extends TestCase
{
    public function test_it_deletes_uploaded_files_and_reseeds_the_database(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('quotations/test-quote.pdf', 'fake pdf content');
        Storage::disk('local')->assertExists('quotations/test-quote.pdf');

        $this->artisan('app:reset-demo')->assertExitCode(0);

        Storage::disk('local')->assertMissing('quotations/test-quote.pdf');
    }
}
