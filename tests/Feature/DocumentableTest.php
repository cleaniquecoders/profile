<?php

use CleaniqueCoders\Profile\Enums\DocumentType;
use CleaniqueCoders\Profile\Models\Document;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has a documents table', function () {
    expect(Schema::hasTable('documents'))->toBeTrue();
});

it('can create a document', function () {
    $document = Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport',
        'file_path' => '/documents/passport.pdf',
        'file_type' => 'pdf',
        'file_size' => 1024000,
        'issued_at' => now()->subYear(),
        'expires_at' => now()->addYears(5),
        'is_verified' => true,
    ]);

    expect($document)->not->toBeNull()
        ->and($document->title)->toBe('Passport')
        ->and($document->type)->toBe(DocumentType::PASSPORT)
        ->and($document->file_path)->toBe('/documents/passport.pdf')
        ->and($document->is_verified)->toBeTrue();
});

it('document belongs to documentable model', function () {
    $document = Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'ID Card',
        'file_path' => '/documents/id.jpg',
    ]);

    expect($document->documentable)->toBeInstanceOf(User::class)
        ->and($document->documentable->id)->toBe($this->user->id);
});

it('can filter documents by verified scope', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Verified Passport',
        'file_path' => '/documents/passport.pdf',
        'is_verified' => true,
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'Unverified ID',
        'file_path' => '/documents/id.jpg',
        'is_verified' => false,
    ]);

    $verifiedDocuments = Document::verified()->get();

    expect($verifiedDocuments)->toHaveCount(1)
        ->and($verifiedDocuments->first()->title)->toBe('Verified Passport');
});

it('can filter documents by type scope', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport',
        'file_path' => '/documents/passport.pdf',
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'ID Card',
        'file_path' => '/documents/id.jpg',
    ]);

    $passports = Document::type(DocumentType::PASSPORT->value)->get();

    expect($passports)->toHaveCount(1)
        ->and($passports->first()->type)->toBe(DocumentType::PASSPORT);
});

it('can filter active documents using scope', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Active Passport',
        'file_path' => '/documents/passport1.pdf',
        'expires_at' => now()->addYear(),
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::DRIVER_LICENSE,
        'title' => 'No Expiry License',
        'file_path' => '/documents/license.pdf',
        'expires_at' => null,
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'Expired ID',
        'file_path' => '/documents/id.jpg',
        'expires_at' => now()->subDay(),
    ]);

    $activeDocuments = Document::active()->get();

    expect($activeDocuments)->toHaveCount(2);
});

it('can filter expired documents using scope', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Expired Passport',
        'file_path' => '/documents/passport.pdf',
        'expires_at' => now()->subYear(),
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'Active ID',
        'file_path' => '/documents/id.jpg',
        'expires_at' => now()->addYear(),
    ]);

    $expiredDocuments = Document::expired()->get();

    expect($expiredDocuments)->toHaveCount(1)
        ->and($expiredDocuments->first()->title)->toBe('Expired Passport');
});

it('user can access documents via HasProfile trait', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport',
        'file_path' => '/documents/passport.pdf',
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'ID Card',
        'file_path' => '/documents/id.jpg',
    ]);

    $documents = $this->user->documents;

    expect($documents)->toHaveCount(2);
});

it('user can get active documents', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Active Passport',
        'file_path' => '/documents/passport.pdf',
        'expires_at' => now()->addYear(),
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'Expired ID',
        'file_path' => '/documents/id.jpg',
        'expires_at' => now()->subDay(),
    ]);

    $activeDocuments = $this->user->activeDocuments();

    expect($activeDocuments)->toHaveCount(1)
        ->and($activeDocuments->first()->title)->toBe('Active Passport');
});

it('user can get expired documents', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Expired Passport',
        'file_path' => '/documents/passport.pdf',
        'expires_at' => now()->subYear(),
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'Active ID',
        'file_path' => '/documents/id.jpg',
        'expires_at' => now()->addYear(),
    ]);

    $expiredDocuments = $this->user->expiredDocuments();

    expect($expiredDocuments)->toHaveCount(1)
        ->and($expiredDocuments->first()->title)->toBe('Expired Passport');
});

it('user can get documents by type', function () {
    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport 1',
        'file_path' => '/documents/passport1.pdf',
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport 2',
        'file_path' => '/documents/passport2.pdf',
    ]);

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::ID,
        'title' => 'ID Card',
        'file_path' => '/documents/id.jpg',
    ]);

    $passports = $this->user->getDocumentsByType(DocumentType::PASSPORT->value);

    expect($passports)->toHaveCount(2);
});

it('user can check if has active documents', function () {
    expect($this->user->hasActiveDocuments())->toBeFalse();

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Active Passport',
        'file_path' => '/documents/passport.pdf',
        'expires_at' => now()->addYear(),
    ]);

    expect($this->user->hasActiveDocuments())->toBeTrue();
});

it('user can check if has document of specific type', function () {
    expect($this->user->hasDocument(DocumentType::PASSPORT->value))->toBeFalse();

    Document::create([
        'documentable_id' => $this->user->id,
        'documentable_type' => get_class($this->user),
        'type' => DocumentType::PASSPORT,
        'title' => 'Passport',
        'file_path' => '/documents/passport.pdf',
    ]);

    expect($this->user->hasDocument(DocumentType::PASSPORT->value))->toBeTrue()
        ->and($this->user->hasDocument(DocumentType::ID->value))->toBeFalse();
});
