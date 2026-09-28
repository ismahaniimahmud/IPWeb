<?php

use App\Models\Action;
use App\Models\User;

test('the application returns a successful response', function () {
    $response = $this->actingAs(User::factory()->create())->get('/');

    $response->assertOk()->assertSee('Papan Pemantauan');
});

test('actions can be created, updated, and deleted', function () {
    $payload = [
        'action_code' => 'TEST-01',
        'reference' => 'Test reference',
        'theme' => 'Testing',
        'required_action' => 'Test action',
        'owner' => 'QA',
        'target' => 'Q4 2026',
        'due_date' => '2026-09-30',
        'status' => 'Belum disahkan',
        'priority' => 'Tinggi',
        'basis' => 'Cadangan',
        'progress' => 'Belum bermula',
        'evidence' => null,
        'updated_on' => '2026-09-16',
        'source_note' => 'Test note',
        'meeting_date' => '2026-09-16',
    ];

    $this->actingAs(User::factory()->create());

    $this->post(route('tindakan.store'), $payload)->assertRedirect(route('tindakan.index'));
    $action = Action::query()->where('action_code', 'TEST-01')->firstOrFail();
    expect($action->theme)->toBe('Testing');

    $this->put(route('tindakan.update', $action), [...$payload, 'theme' => 'Updated theme'])
        ->assertRedirect(route('tindakan.index'));
    expect($action->refresh()->theme)->toBe('Updated theme');
    expect(substr($action->getRawOriginal('due_date'), 0, 10))->toBe('2026-09-30');

    $this->delete(route('tindakan.destroy', $action))->assertRedirect(route('tindakan.index'));
    expect(Action::query()->whereKey($action->id)->exists())->toBeFalse();
});

test('dashboard lists actions by id ascending', function () {
    $this->actingAs(User::factory()->create());

    Action::query()->create([
        'action_code' => 'II-05',
        'reference' => 'Ref 05',
        'theme' => 'Theme 05',
        'required_action' => 'Action 05',
        'owner' => 'Owner 05',
        'target' => 'Target 05',
        'status' => 'Dalam tindakan',
        'priority' => 'Tinggi',
        'basis' => 'Cadangan',
        'progress' => 'On track',
        'evidence' => null,
        'updated_on' => '2026-09-16',
        'source_note' => 'Note 05',
        'meeting_date' => '2026-09-16',
    ]);

    Action::query()->create([
        'action_code' => 'II-03',
        'reference' => 'Ref 03',
        'theme' => 'Theme 03',
        'required_action' => 'Action 03',
        'owner' => 'Owner 03',
        'target' => 'Target 03',
        'status' => 'Belum disahkan',
        'priority' => 'Sederhana',
        'basis' => 'Cadangan',
        'progress' => 'Needs review',
        'evidence' => null,
        'updated_on' => '2026-09-16',
        'source_note' => 'Note 03',
        'meeting_date' => '2026-09-16',
    ]);

    Action::query()->create([
        'action_code' => 'II-04',
        'reference' => 'Ref 04',
        'theme' => 'Theme 04',
        'required_action' => 'Action 04',
        'owner' => 'Owner 04',
        'target' => 'Target 04',
        'status' => 'Menunggu pihak luar',
        'priority' => 'Rendah',
        'basis' => 'Cadangan',
        'progress' => 'Waiting',
        'evidence' => null,
        'updated_on' => '2026-09-16',
        'source_note' => 'Note 04',
        'meeting_date' => '2026-09-16',
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('No')
        ->assertSeeInOrder(['No', 'II-03', 'II-04', 'II-05']);
});

test('users can log in and log out', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('secret-password'),
    ]);

    $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'))->assertRedirect(route('login'));
    $this->assertGuest();
});

test('dashboard highlights overdue open actions', function () {
    $this->actingAs(User::factory()->create());

    Action::factory()->create([
        'action_code' => 'OVERDUE-01',
        'due_date' => now()->subDay(),
        'status' => 'Dalam tindakan',
    ]);

    Action::factory()->create([
        'action_code' => 'DONE-01',
        'due_date' => now()->subDay(),
        'status' => 'Selesai',
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('LEWAT TEMPOH')
        ->assertSee('1');
});

test('actions can be filtered by deadline health', function () {
    $this->actingAs(User::factory()->create());

    Action::factory()->create([
        'action_code' => 'OVERDUE-01',
        'due_date' => now()->subDay(),
        'status' => 'Dalam tindakan',
    ]);

    Action::factory()->create([
        'action_code' => 'UPCOMING-01',
        'due_date' => now()->addDays(3),
        'status' => 'Dalam tindakan',
    ]);

    $this->get(route('tindakan.index', ['deadline' => 'overdue']))
        ->assertOk()
        ->assertSee('OVERDUE-01')
        ->assertDontSee('UPCOMING-01');
});

test('guests can create an account', function () {
    $this->post(route('register'), [
        'name' => 'Pentadbir Baharu',
        'email' => 'new-admin@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'new-admin@example.com']);
});

test('registration rejects an existing email', function () {
    $user = User::factory()->create(['email' => 'used@example.com']);

    $this->from(route('register'))
        ->post(route('register'), [
            'name' => 'Another User',
            'email' => $user->email,
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])
        ->assertRedirect(route('register'))
        ->assertSessionHasErrors('email');
});

test('guests are redirected to login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});
