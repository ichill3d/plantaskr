To run factories in Laravel, follow these steps:

1. **Ensure Factory Exists**:
   Check or create a factory for your model, e.g., `OrganizationFactory`:
   ```bash
   php artisan make:factory OrganizationFactory --model=Organization
   ```

2. **Define Factory Data**:
   Open `database/factories/OrganizationFactory.php` and define how fake data should look:
   ```php
   public function definition()
   {
       return [
           'name' => $this->faker->company(),
           'description' => $this->faker->sentence(),
       ];
   }
   ```

3. **Run the Factory in Tinker**:
   Open Laravel Tinker:
   ```bash
   php artisan tinker
   ```

   Use the factory to create records:
   ```php
   Organization::factory()->count(10)->create();
   ```

4. **Seed Factories via DatabaseSeeder**:
   Add the factory in `database/seeders/DatabaseSeeder.php`:
   ```php
   public function run()
   {
       Organization::factory()->count(10)->create();
   }
   ```

   Run the seeder:
   ```bash
   php artisan db:seed
   ```

This will populate your database with the specified number of factory-generated records.
