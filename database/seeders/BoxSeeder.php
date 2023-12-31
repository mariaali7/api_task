<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Box;
use Database\Factories\BoxFactory;
use App\Models\User;


class BoxSeeder extends Seeder
{
  public function run()
  {
    BoxFactory::new()->count(5)->create();
  }
}