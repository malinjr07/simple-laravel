<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'media', 'tags']);

        // Apply filters if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('is_on_sale')) {
            $query->onSale();
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Sort options
        $sortField = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['name', 'price', 'created_at', 'stock'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image_id' => 'nullable|exists:media,media_id',
            'sku' => 'nullable|string|max:50|unique:products',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_on_sale' => 'nullable|boolean',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'tagId' => 'nullable|array',
            'tagId.*' => 'integer|exists:tags,id',
            'tagName' => 'nullable|array',
            'tagName.*' => 'string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create the product
            $productData = $validator->validated();

            // Remove tag fields from product data
            $tagIds = $request->input('tagId', []);
            $tagNames = $request->input('tagName', []);

            unset($productData['tagId'], $productData['tagName']);

            // Generate slug if not provided
            if (empty($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']);
            }

            $product = Product::create($productData);

            // Handle tags
            $this->processTags($product, $tagIds, $tagNames);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product->load(['category', 'media', 'tags'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create product: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with(['category', 'media', 'tags'])
            ->find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Update the specified product.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image_id' => 'nullable|exists:media,media_id',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $id,
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_on_sale' => 'nullable|boolean',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'tagId' => 'nullable|array',
            'tagId.*' => 'integer|exists:tags,id',
            'tagName' => 'nullable|array',
            'tagName.*' => 'string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update product data
            $productData = $validator->validated();

            // Remove tag fields from product data
            $tagIds = $request->input('tagId', []);
            $tagNames = $request->input('tagName', []);

            unset($productData['tagId'], $productData['tagName']);

            // Generate slug if name changed but slug not provided
            if (isset($productData['name']) && !isset($productData['slug'])) {
                $productData['slug'] = Str::slug($productData['name']);
            }

            $product->update($productData);

            // Handle tags
            $this->processTags($product, $tagIds, $tagNames);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product->load(['category', 'media', 'tags'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update product: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        try {
            // The product model boot method will handle detaching tags
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete product: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process tags for a product, handling both tag IDs and tag names.
     *
     * @param Product $product
     * @param array $tagIds
     * @param array $tagNames
     * @return void
     */
    private function processTags(Product $product, array $tagIds = [], array $tagNames = [])
    {
        $allTagIds = $tagIds;

        // Process tag names - create new tags if they don't exist
        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(['name' => $name, 'slug' => Str::slug($name)]);
            $allTagIds[] = $tag->id;
        }

        // Remove duplicates
        $allTagIds = array_unique($allTagIds);

        // Sync tags with the product
        $product->tags()->sync($allTagIds);

        // Update tag_id in the product table
        if (!empty($allTagIds)) {
            // Store the first tag ID in the tag_id field
            $product->tag_id = $allTagIds[0];
            $product->save();
        } else {
            // No tags, set tag_id to null
            $product->tag_id = null;
            $product->save();
        }
    }

    /**
     * Update product stock.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $product->update(['stock' => $request->stock]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product stock updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Toggle product active status.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $product->update(['is_active' => !$product->is_active]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product status toggled successfully',
            'data' => $product
        ]);
    }

    /**
     * Toggle product featured status.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFeatured($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $product->update(['is_featured' => !$product->is_featured]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product featured status toggled successfully',
            'data' => $product
        ]);
    }

    /**
     * Set product sale information.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSale(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_on_sale' => 'required|boolean',
            'sale_price' => 'required_if:is_on_sale,true|nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $product->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Product sale information updated successfully',
            'data' => $product
        ]);
    }
}