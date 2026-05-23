<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateEcommerceTables extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('categories', function (Blueprint $table) {
                if (! Schema::hasColumn('categories', 'slug')) {
                    $table->string('slug')->unique()->after('name');
                }
                if (! Schema::hasColumn('categories', 'description')) {
                    $table->string('description')->nullable();
                }
                if (! Schema::hasColumn('categories', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('categories', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('sku')->nullable();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('discount_percent', 5, 2)->default(0);
                $table->integer('stock')->default(0);
                $table->string('image_url', 500)->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'sku')) {
                    $table->string('sku')->nullable();
                }
                if (! Schema::hasColumn('products', 'description')) {
                    $table->text('description')->nullable();
                }
                if (! Schema::hasColumn('products', 'stock')) {
                    $table->integer('stock')->default(0);
                }
                if (! Schema::hasColumn('products', 'image_url')) {
                    $table->string('image_url', 500)->nullable();
                }
                if (! Schema::hasColumn('products', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
                if (! Schema::hasColumn('products', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::hasColumn('products', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('home_banners')) {
            Schema::create('home_banners', function (Blueprint $table) {
                $table->id();
                $table->string('eyebrow')->default('Wholesale ready ecommerce');
                $table->string('title');
                $table->text('subtitle')->nullable();
                $table->string('image_url', 500)->nullable();
                $table->string('cta_text')->default('Shop all items');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->string('customer_email')->nullable();
                $table->text('delivery_address');
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount_total', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2);
                $table->string('status')->default('pending');
                $table->boolean('stock_update')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'stock_update')) {
                    $table->boolean('stock_update')->default(false)->after('status');
                }
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedInteger('product_id');
                $table->string('product_name');
                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('selling_price', 10, 2);
                $table->decimal('line_total', 10, 2);
                $table->timestamps();
            });
        }

        $this->seedBaseData();
    }

    private function seedBaseData()
    {
        $categories = [
            ['Mycola', 'Soft drinks and cola favorites.'],
            ['Byraha', 'Frozen and chilled poultry items.'],
            ['Britol', 'Household and grocery essentials.'],
            ['Mineral Water', 'Bottled drinking water.'],
            ['Kandos', 'Chocolate and sweet treats.'],
            ['Watawala Tea', 'Tea packs and daily blends.'],
            ['Hemas', 'Personal care and household products.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => Str::slug($category[0])],
                ['name' => $category[0], 'description' => $category[1], 'updated_at' => now()]
            );
        }

        if (DB::table('products')->count() === 0) {
            $ids = DB::table('categories')->pluck('id', 'slug');
            $products = [
                ['mycola', 'Mycola Original 1L', 390, 8, 38, 'Premium cola bottle for family meals.', 'https://images.unsplash.com/photo-1581636625402-29b2a704ef13?auto=format&fit=crop&w=1200&q=80', 1],
                ['byraha', 'Byraha Chicken Sausages', 740, 12, 24, 'Frozen chicken sausages for quick service.', 'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?auto=format&fit=crop&w=1200&q=80', 1],
                ['kandos', 'Kandos Milk Chocolate', 450, 7, 42, 'Classic chocolate bar with rich cocoa taste.', 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?auto=format&fit=crop&w=1200&q=80', 1],
                ['watawala-tea', 'Watawala Tea 400g', 980, 9, 28, 'Bold black tea blend for daily retail sales.', 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?auto=format&fit=crop&w=1200&q=80', 1],
                ['mineral-water', 'Mineral Water 1.5L', 160, 0, 120, 'Clean bottled drinking water for every order.', 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=1200&q=80', 0],
                ['hemas', 'Hemas Baby Soap Pack', 520, 11, 33, 'Gentle personal care soap pack.', 'https://images.unsplash.com/photo-1607006344380-b6775a0824a7?auto=format&fit=crop&w=1200&q=80', 0],
                ['britol', 'Britol Dish Liquid', 320, 15, 64, 'Daily-use cleaning liquid for home shelves.', 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?auto=format&fit=crop&w=1200&q=80', 0],
            ];

            foreach ($products as $product) {
                DB::table('products')->insert([
                    'category_id' => $ids[$product[0]],
                    'name' => $product[1],
                    'sku' => strtoupper(Str::slug($product[1], '-')),
                    'price' => $product[2],
                    'discount_percent' => $product[3],
                    'stock' => $product[4],
                    'description' => $product[5],
                    'image_url' => $product[6],
                    'is_featured' => $product[7],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (DB::table('home_banners')->count() === 0) {
            DB::table('home_banners')->insert([
                'eyebrow' => 'Wholesale ready ecommerce',
                'title' => 'Build every order from trusted daily brands.',
                'subtitle' => 'Search categories, compare discounts, add items to cart, and checkout from a responsive industrial-grade storefront.',
                'image_url' => 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?auto=format&fit=crop&w=1600&q=80',
                'cta_text' => 'Shop all items',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@my-bussiness.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'password_hash' => Hash::make('admin123'),
                'role' => 'super_admin',
                'updated_at' => now(),
            ]
        );
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('home_banners');
    }
}
