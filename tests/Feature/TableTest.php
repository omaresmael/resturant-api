<?php

use App\Models\Customer;
use App\Models\Table;

beforeEach(function () {
    $this->table = Table::factory()->create([
        'capacity' => 4,
    ]);
    $this->customer = Customer::factory()->create();

    $this->table->customers()->attach($this->customer, [
        'from_time' => '2023-04-03 10:00:00',
        'to_time' => '2023-04-03 12:00:00',
    ]);

});

it('check the availability of the table', function () {

    $response = $this->getJson(route('table.availability', [
        'table' => $this->table->id,
        'from_time' => '2023-04-03 15:00:00',
        'to_time' => '2023-04-03 16:00:00',
        'guests' => 3,
    ]));
    $this->assertEquals('Table is available', $response->json('message'));

    $response = $this->getJson(route('table.availability', [
        'table' => $this->table->id,
        'from_time' => '2023-04-03 11:00:00',
        'to_time' => '2023-04-03 13:00:00',
        'guests' => 3,
    ]));

    $this->assertEquals('Table is not available at this time', $response->json('message'));


});

it('reserves tha table if available', function () {
    $response = $this->postJson(route('table.reserve', [
        'table' => $this->table->id,
        'customer' => $this->customer->id,
        'from_time' => '2023-04-03 11:00:00',
        'to_time' => '2023-04-03 12:00:00',
    ]));

    $this->assertEquals('Table is not available at this time', $response->json('message'));

    $response = $this->postJson(route('table.reserve', [
        'table' => $this->table->id,
        'customer' => $this->customer->id,
        'from_time' => '2023-04-03 14:00:00',
        'to_time' => '2023-04-03 16:00:00',
    ]));

    $this->assertEquals('Table is reserved successfully', $response->json('message'));
    $this->assertDatabaseHas('reservations', [
        'customer_id' => $this->customer->id,
        'table_id' => $this->table->id,
        'from_time' => '2023-04-03 14:00:00',
        'to_time' => '2023-04-03 16:00:00',
    ]);
});

it('wait-list customer if the table is not available', function () {

})->todo();
