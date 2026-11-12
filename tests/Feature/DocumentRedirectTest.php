<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRedirectTest extends TestCase
{
    public function test_root_and_locale_pdf_links_redirect_to_media(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/GUTGAS_CR_Manual_UKR.pdf', '%PDF');

        $this->get('/GUTGAS_CR_Manual_UKR.pdf')
            ->assertRedirect('/storage/media/GUTGAS_CR_Manual_UKR.pdf')
            ->assertStatus(301);
        $this->get('/ua/GUTGAS_CR_Manual_UKR.pdf')
            ->assertRedirect('/storage/media/GUTGAS_CR_Manual_UKR.pdf');
    }

    public function test_missing_pdf_is_404(): void
    {
        Storage::fake('public');

        $this->get('/missing.pdf')->assertNotFound();
    }
}
