<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EcommerceController extends Controller
{
    public function app()
    {
        return view('app');
    }

    public function state(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'category' => trim((string) $request->query('category', '')),
            'subcategory' => trim((string) $request->query('subcategory', '')),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'min_discount' => $request->query('min_discount'),
        ];

        return response()->json([
            'auth' => ['user' => session('user')],
            'banner' => DB::table('home_banners')->where('is_active', true)->first(),
            'categories' => DB::table('categories')->orderBy('name')->get(),
            'subcategories' => DB::table('subcategories as s')
                ->join('categories as c', 'c.id', '=', 's.category_id')
                ->select('s.*', 'c.name as category_name', 'c.slug as category_slug')
                ->orderBy('c.name')
                ->orderBy('s.name')
                ->get(),
            'products' => $this->productsQuery($filters)->get(),
            'orders' => $this->isSuperAdmin() ? $this->ordersWithItems() : [],
            'csrf' => csrf_token(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = DB::table('users')->where('email', $request->email)->first();
        $hash = $user->password ?? $user->password_hash ?? null;

        if (! $user || ! $hash || ! Hash::check($request->password, $hash)) {
            return response()->json(['message' => 'Invalid email or password.'], 422);
        }

        session(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'customer',
        ]]);

        return response()->json(['message' => 'Logged in.', 'user' => session('user')]);
    }

    public function logout()
    {
        session()->forget('user');
        return response()->json(['message' => 'Logged out.']);
    }

    public function storeCategory(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:255',
        ]);

        DB::table('categories')->insert([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Category created.']);
    }

    public function updateCategory(Request $request, int $id)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:255',
        ]);

        DB::table('categories')->where('id', $id)->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Category updated.']);
    }

    public function deleteCategory(int $id)
    {
        $this->authorizeSuperAdmin();
        DB::table('categories')->where('id', $id)->delete();
        return response()->json(['message' => 'Category deleted.']);
    }

    public function storeSubcategory(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:255',
        ]);

        DB::table('subcategories')->insert([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Subcategory created.']);
    }

    public function updateSubcategory(Request $request, int $id)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:255',
        ]);

        DB::table('subcategories')->where('id', $id)->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Subcategory updated.']);
    }

    public function deleteSubcategory(int $id)
    {
        $this->authorizeSuperAdmin();
        DB::table('products')->where('subcategory_id', $id)->update(['subcategory_id' => null, 'updated_at' => now()]);
        DB::table('subcategories')->where('id', $id)->delete();
        return response()->json(['message' => 'Subcategory deleted.']);
    }

    public function storeProduct(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $this->productData($request);
        $data['created_at'] = now();
        $data['updated_at'] = now();
        DB::table('products')->insert($data);
        return response()->json(['message' => 'Item created.']);
    }

    public function updateProduct(Request $request, int $id)
    {
        $this->authorizeSuperAdmin();
        $data = $this->productData($request);
        $data['updated_at'] = now();
        DB::table('products')->where('id', $id)->update($data);
        return response()->json(['message' => 'Item updated.']);
    }

    public function deleteProduct(int $id)
    {
        $this->authorizeSuperAdmin();
        DB::table('products')->where('id', $id)->delete();
        return response()->json(['message' => 'Item deleted.']);
    }

    public function updateBanner(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'eyebrow' => 'required|string|max:120',
            'title' => 'required|string|max:180',
            'subtitle' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'cta_text' => 'required|string|max:80',
        ]);

        $banner = DB::table('home_banners')->where('is_active', true)->first();
        if ($banner) {
            DB::table('home_banners')->where('id', $banner->id)->update($data + ['updated_at' => now()]);
        } else {
            DB::table('home_banners')->insert($data + ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        }

        return response()->json(['message' => 'Home banner updated.']);
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'customer.name' => 'required|string|max:120',
            'customer.phone' => 'required|string|max:40',
            'customer.email' => 'nullable|email|max:160',
            'customer.address' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $ids = collect($data['items'])->pluck('id')->all();
        $products = DB::table('products')->whereIn('id', $ids)->get()->keyBy('id');
        $subtotal = 0;
        $discountTotal = 0;
        $orderLines = [];

        foreach ($data['items'] as $item) {
            $product = $products[$item['id']] ?? null;
            if (! $product) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $selling = round($product->price - ($product->price * $product->discount_percent / 100), 2);
            $subtotal += $product->price * $quantity;
            $discountTotal += ($product->price - $selling) * $quantity;
            $orderLines[] = compact('product', 'quantity', 'selling');
        }

        if ($orderLines === []) {
            return response()->json(['message' => 'Cart has no valid items.'], 422);
        }

        $orderId = DB::table('orders')->insertGetId([
            'customer_name' => $data['customer']['name'],
            'customer_phone' => $data['customer']['phone'],
            'customer_email' => $data['customer']['email'] ?? null,
            'delivery_address' => $data['customer']['address'],
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'grand_total' => $subtotal - $discountTotal,
            'status' => 'submitted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($orderLines as $line) {
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $line['product']->id,
                'product_name' => $line['product']->name,
                'quantity' => $line['quantity'],
                'unit_price' => $line['product']->price,
                'selling_price' => $line['selling'],
                'line_total' => $line['selling'] * $line['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Order placed.', 'order_id' => $orderId]);
    }

    public function updateOrderStatus(Request $request, int $id)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'status' => 'required|in:submitted,ready,complete',
        ]);

        DB::transaction(function () use ($id, $data) {
            $order = DB::table('orders')->where('id', $id)->lockForUpdate()->first();

            if (! $order) {
                abort(404, 'Order not found.');
            }

            if ($data['status'] === 'complete' && ! (bool) $order->stock_update) {
                $this->deductOrderStock($id);
            }

            DB::table('orders')->where('id', $id)->update([
                'status' => $data['status'],
                'stock_update' => (bool) $order->stock_update || $data['status'] === 'complete',
                'updated_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Order status updated.']);
    }

    public function updateOrder(Request $request, int $id)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_phone' => 'required|string|max:40',
            'customer_email' => 'nullable|email|max:160',
            'delivery_address' => 'required|string|max:500',
            'status' => 'required|in:submitted,ready,complete',
            'items' => 'nullable|array',
            'items.*.id' => 'required_with:items|integer',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $subtotal = 0;
        $discountTotal = 0;
        if (array_key_exists('items', $data)) {
            foreach ($data['items'] as $item) {
                $quantity = (int) $item['quantity'];
                $unitPrice = round((float) $item['unit_price'], 2);
                $discountPercent = round((float) ($item['discount_percent'] ?? 0), 2);
                $sellingPrice = round($unitPrice - ($unitPrice * $discountPercent / 100), 2);
                $lineTotal = round($sellingPrice * $quantity, 2);

                $subtotal += $unitPrice * $quantity;
                $discountTotal += ($unitPrice - $sellingPrice) * $quantity;

                DB::table('order_items')
                    ->where('order_id', $id)
                    ->where('id', $item['id'])
                    ->update([
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'selling_price' => $sellingPrice,
                        'line_total' => $lineTotal,
                        'updated_at' => now(),
                    ]);
            }
        } else {
            $items = DB::table('order_items')->where('order_id', $id)->get();
            foreach ($items as $item) {
                $subtotal += $item->unit_price * $item->quantity;
                $discountTotal += ($item->unit_price - $item->selling_price) * $item->quantity;
            }
        }

        DB::transaction(function () use ($id, $data, $subtotal, $discountTotal) {
            $order = DB::table('orders')->where('id', $id)->lockForUpdate()->first();

            if (! $order) {
                abort(404, 'Order not found.');
            }

            if ($data['status'] === 'complete' && ! (bool) $order->stock_update) {
                $this->deductOrderStock($id);
            }

            DB::table('orders')->where('id', $id)->update([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'delivery_address' => $data['delivery_address'],
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'grand_total' => $subtotal - $discountTotal,
                'status' => $data['status'],
                'stock_update' => (bool) $order->stock_update || $data['status'] === 'complete',
                'updated_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Order updated.']);
    }

    public function deleteOrder(int $id)
    {
        $this->authorizeSuperAdmin();

        DB::table('order_items')->where('order_id', $id)->delete();
        DB::table('orders')->where('id', $id)->delete();

        return response()->json(['message' => 'Order deleted.']);
    }

    private function ordersWithItems()
    {
        $orders = DB::table('orders')->latest()->limit(40)->get();
        $items = DB::table('order_items as oi')
            ->leftJoin('products as p', 'p.id', '=', 'oi.product_id')
            ->select(
                'oi.*',
                'p.image_url',
                'p.sku'
            )
            ->whereIn('oi.order_id', $orders->pluck('id')->all())
            ->orderBy('oi.id')
            ->get()
            ->groupBy('order_id');

        return $orders->map(function ($order) use ($items) {
            $order->items = ($items[$order->id] ?? collect())->values();
            return $order;
        });
    }

    private function deductOrderStock(int $orderId): void
    {
        $items = DB::table('order_items')->where('order_id', $orderId)->get();

        foreach ($items as $item) {
            $product = DB::table('products')->where('id', $item->product_id)->lockForUpdate()->first();

            if (! $product) {
                continue;
            }

            DB::table('products')->where('id', $item->product_id)->update([
                'stock' => max(0, (int) $product->stock - (int) $item->quantity),
                'updated_at' => now(),
            ]);
        }
    }

    private function productsQuery(array $filters)
    {
        $query = DB::table('products as p')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('subcategories as s', 's.id', '=', 'p.subcategory_id')
            ->select('p.*', 'c.name as category_name', 'c.slug as category_slug', 's.name as subcategory_name', 's.slug as subcategory_slug')
            ->selectRaw('ROUND(p.price - (p.price * p.discount_percent / 100), 2) as selling_price');

        if ($filters['search'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('p.name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('p.description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('c.name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('s.name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['category'] !== '') {
            $query->where('c.slug', $filters['category']);
        }

        if ($filters['subcategory'] !== '') {
            $query->where('s.slug', $filters['subcategory']);
        }

        if (is_numeric($filters['min_price'])) {
            $query->where('p.price', '>=', (float) $filters['min_price']);
        }

        if (is_numeric($filters['max_price'])) {
            $query->where('p.price', '<=', (float) $filters['max_price']);
        }

        if (is_numeric($filters['min_discount'])) {
            $query->where('p.discount_percent', '>=', (float) $filters['min_discount']);
        }

        return $query->orderByDesc('p.is_featured')->orderByDesc('p.id');
    }

    private function productData(Request $request): array
    {
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:subcategories,id',
            'name' => 'required|string|max:160',
            'sku' => 'nullable|string|max:80',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'stock' => 'nullable|integer|min:0',
            'image_url' => 'nullable|string|max:500',
            'is_featured' => 'nullable|boolean',
        ]);

        if (! empty($data['subcategory_id'])) {
            $belongsToCategory = DB::table('subcategories')
                ->where('id', $data['subcategory_id'])
                ->where('category_id', $data['category_id'])
                ->exists();

            abort_unless($belongsToCategory, 422, 'Selected subcategory does not belong to this category.');
        }

        return [
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id'] ?? null,
            'name' => $data['name'],
            'sku' => $data['sku'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'stock' => $data['stock'] ?? 0,
            'image_url' => $data['image_url'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ];
    }

    private function isSuperAdmin(): bool
    {
        return (session('user.role') === 'super_admin');
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless($this->isSuperAdmin(), 403, 'Only super admin can perform this action.');
    }
}
