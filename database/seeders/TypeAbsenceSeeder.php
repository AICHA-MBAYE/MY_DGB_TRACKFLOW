<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeAbsence;

class TypeAbsenceSeeder extends Seeder
{
    public function run()
    {
        TypeAbsence::insert([
            ['libelle' => 'Congé '],
            ['libelle' => 'Maladie'],
            ['libelle' => 'Absence exceptionnelle'],
            ['libelle' => 'Congé maternité'],
           
        ]);
    }
}
