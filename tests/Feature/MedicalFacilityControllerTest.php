<?php

use Tests\TestCase;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use App\Models\MedicalFacility;
use function Pest\Laravel\post;
use App\Models\MedicalFacilityType;
use function Pest\Laravel\actingAs;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->type = MedicalFacilityType::factory()->create();
});

it('can view the medical facilities index page', function () {
    actingAs($this->user);
    $response = get(route(get_area_name().'.medical-facilities.index'));
    $response->assertStatus(200);
    $response->assertViewIs('general.medical-facilities.index');
});

it('can create a medical facility via the controller', function () {
    actingAs($this->user);
    $data = [
        'serial_number' => 'SN123',
        'name' => 'Controller Test Facility',
        'medical_facility_type_id' => $this->type->id,
        'address' => '123 Main St',
        'phone_number' => '0123456789',
        'commerical_number' => 'C12345',
        'date' => '2024-01-01',
        'activity_type' => 'commercial_record'
    ];

    $response = post(route(get_area_name().'.medical-facilities.store'), $data);
    $response->assertRedirect(route(get_area_name().'.medical-facilities.index'));
    $response->assertSessionHas('success', 'تم إنشاء منشأة طبية جديدة بنجاح.');

    expect(\App\Models\MedicalFacility::where('name', 'Controller Test Facility')->exists())->toBeTrue();
});

it('can update a medical facility via the controller', function () {
    actingAs($this->user);
    $facility = MedicalFacility::factory()->create(['name' => 'Before Update']);

    $data = [
        'serial_number' => 'SN456',
        'name' => 'After Update',
        'medical_facility_type_id' => $this->type->id,
        'address' => '456 Another St',
        'phone_number' => '0987654321',
        'commerical_number' => 'C67890',
        'activity_start_date' => '2024-02-01',
    ];

    $response = put(route(get_area_name().'.medical-facilities.update', $facility->id), $data);
    $response->assertRedirect(route(get_area_name().'.medical-facilities.index'));
    $response->assertSessionHas('success', 'تم تحديث بيانات منشأة طبية بنجاح.');
    expect($facility->fresh()->name)->toBe('After Update');
});
