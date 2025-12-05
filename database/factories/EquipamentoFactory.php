<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Equipamento;
use App\Domain\Enums\TipoEquipamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipamentoFactory extends Factory
{
    protected $model = Equipamento::class;

    public function definition(): array
    {
        $tipo = $this->faker->randomElement(TipoEquipamento::cases());
        
        return [
            'nome' => $tipo->value . ' ' . $this->faker->word(),
            'tipo' => $tipo,
            'preco_unitario' => $this->faker->randomFloat(2, 10, 1000),
            'estoque_atual' => $this->faker->numberBetween(0, 100),
            'ativo' => $this->faker->boolean(90),
        ];
    }
}