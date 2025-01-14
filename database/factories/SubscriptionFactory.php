<?php

namespace LucasDotDev\Soulbscription\Database\Factories;

use LucasDotDev\Soulbscription\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use LucasDotDev\Soulbscription\Models\Subscription;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plan_id'       => Plan::factory(),
            'canceled_at'   => null,
            'started_at'    => $this->faker->dateTime(),
            'suppressed_at' => null,
            'expired_at'    => $this->faker->dateTime(),
            'was_switched'  => false,
        ];
    }

    public function canceled()
    {
        return $this->state(fn (array $attributes) => [
            'canceled_at' => $this->faker->dateTime(),
        ]);
    }

    public function NotCanceled()
    {
        return $this->state(fn (array $attributes) => [
            'canceled_at' => null,
        ]);
    }

    public function expired()
    {
        return $this->state(fn (array $attributes) => [
            'expired_at' => $this->faker->dateTime(),
        ]);
    }

    public function started()
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => $this->faker->dateTime(),
        ]);
    }

    public function notStarted()
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => $this->faker->dateTimeBetween('now', '+30 years'),
        ]);
    }

    public function suppressed()
    {
        return $this->state(fn (array $attributes) => [
            'suppressed_at' => $this->faker->dateTime(),
        ]);
    }

    public function NotSuppressed()
    {
        return $this->state(fn (array $attributes) => [
            'suppressed_at' => null,
        ]);
    }

    public function switched()
    {
        return $this->state(fn (array $attributes) => [
            'was_switched' => true,
        ]);
    }

    public function notSwitched()
    {
        return $this->state(fn (array $attributes) => [
            'was_switched' => false,
        ]);
    }
}
