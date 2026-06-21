<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddProductSubcategories extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('subcategories')) {
            Schema::create('subcategories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('slug');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->unique(['category_id', 'slug']);
            });
        }

        if (! Schema::hasColumn('products', 'subcategory_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id');
                $table->foreign('subcategory_id')->references('id')->on('subcategories')->nullOnDelete();
            });
        }

        $this->seedHemasSubcategories();
    }

    private function seedHemasSubcategories(): void
    {
        $hemasId = DB::table('categories')->where('slug', 'hemas')->value('id');

        if (! $hemasId) {
            return;
        }

        $subcategories = [
            ['Komarika', 'Hair and herbal care products.'],
            ['Diva', 'Laundry and household cleaning products.'],
            ['Baby Cheramy', 'Baby care essentials.'],
            ['Dandex', 'Anti-dandruff hair care products.'],
            ['Gold', 'Personal care and grooming products.'],
            ['Goya', 'Fragrance and personal care products.'],
            ['Clogard', 'Oral care products.'],
            ['Prasara', 'Ayurvedic and wellness products.'],
            ['Fems', 'Feminine hygiene products.'],
            ['Velvet', 'Skin care and soap products.'],
        ];

        foreach ($subcategories as $subcategory) {
            DB::table('subcategories')->updateOrInsert(
                ['category_id' => $hemasId, 'slug' => Str::slug($subcategory[0])],
                [
                    'name' => $subcategory[0],
                    'description' => $subcategory[1],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $babyCheramyId = DB::table('subcategories')
            ->where('category_id', $hemasId)
            ->where('slug', 'baby-cheramy')
            ->value('id');

        if ($babyCheramyId) {
            DB::table('products')
                ->where('category_id', $hemasId)
                ->whereNull('subcategory_id')
                ->where('name', 'like', '%Baby%')
                ->update(['subcategory_id' => $babyCheramyId, 'updated_at' => now()]);
        }
    }

    public function down()
    {
        if (Schema::hasColumn('products', 'subcategory_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            });
        }

        Schema::dropIfExists('subcategories');
    }
}
