<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block">Nom *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border p-2 rounded">
    </div>

    <div>
        <label for="category_id" class="block">Catégorie *</label>
        <select name="category_id" id="category_id" class="w-full border p-2 rounded">
            <option value="">-- Choisir une catégorie --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-span-2">
        <label for="description" class="block">Description *</label>
        <textarea name="description" id="description" rows="2" class="w-full border p-2 rounded">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="col-span-2">
        <label for="long_description" class="block">Description longue</label>
        <textarea name="long_description" id="long_description" rows="4" class="w-full border p-2 rounded">{{ old('long_description', $product->long_description ?? '') }}</textarea>
    </div>

    <div>
        <label for="price" class="block">Prix *</label>
        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" class="w-full border p-2 rounded">
    </div>

    <div>
        <label for="stock" class="block">Stock *</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? '') }}" class="w-full border p-2 rounded">
    </div>

    <div class="col-span-2">
        <label for="image" class="block">Image</label>
        <input type="file" name="image" id="image" class="w-full border p-2 rounded">
        @if (!empty($product->image))
            <img src="{{ asset('storage/' . $product->image) }}" alt="Image actuelle" class="w-32 mt-2">
        @endif
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
        {{ $button }}
    </button>
</div>


