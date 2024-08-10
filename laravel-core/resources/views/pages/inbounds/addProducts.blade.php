@extends('layouts.main')
@section('subtitle', "Créer une entrée")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{ route('inbounds_add_products', $inbound) }}" method="post">
    @csrf
    <div class="row">
        <div class="col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Créer une entrée</h5>
                    @if($user->Has_Permission('inbounds_create'))
                    <button type="button" class="btn btn-primary" id="addProductBtn"> Add product </button>
                    @endif
                </div>
                <div class="card-body" id="productContainer">
                    <div class="modal-body m-3 product-entry" data-index="0">
                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-2">Product</label>
                            <select name="products[0][product_id]" class="form-control me-2" required>
                                <option value="">Selectionner le produit</option>
                                @foreach($inbound->rubrique->products as $product)
                                <option value="{{ $product->id }}">{{ $product->designation }}</option>
                                @endforeach
                            </select>
                            <label class="form-label me-2">qte</label>
                            <input type="number" name="products[0][qte]" class="form-control me-2" step="any">
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label">Prix/Un_HT</label>
                            <input type="number" name="products[0][unit_price_excl_tax]" class="ms-2 form-control" step="any">
                            <label class="form-label ms-2">Prix/Un_TTC</label>
                            <input type="number" name="products[0][unit_price_net]" class="ms-2 form-control" readonly>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label">Montant_HT</label>
                            <input type="number" name="products[0][total_amount_excl_tax]" class="ms-2 form-control" readonly>
                            <label class="form-label ms-2">Montant_TTC</label>
                            <input type="number" name="products[0][total_amount_net]" class="ms-2 form-control" readonly>
                        </div>
                        <button type="button" class="btn btn-danger removeBtn d-none">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($user->Has_Permission('inbounds_create'))
    <button type="submit" class="btn btn-primary"> Suivant </button>
    @endif
</form>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addProductBtn = document.getElementById('addProductBtn');
    const productContainer = document.getElementById('productContainer');
    
    let productIndex = 1; // Start from 1 since 0 is already used

    function updateRemoveButtons() {
        const productEntries = document.querySelectorAll('.product-entry');
        productEntries.forEach((entry, index) => {
            const removeBtn = entry.querySelector('.removeBtn');
            if (index === 0) {
                removeBtn.classList.add("d-none");
            } else {
                removeBtn.classList.remove("d-none");
            }
        });
    }

    function updateProductIndices() {
        const productEntries = document.querySelectorAll('.product-entry');
        productEntries.forEach((entry, index) => {
            const inputs = entry.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                input.name = name;
            });
            entry.dataset.index = index;
        });
        productIndex = productEntries.length; // Update the next index
    }

    function calculatePrices(entry) {
        const unitPriceExclTax = parseFloat(entry.querySelector('input[name$="[unit_price_excl_tax]"]').value) || 0;
        const quantity = parseFloat(entry.querySelector('input[name$="[qte]"]').value) || 0;
        const unitPriceNet = unitPriceExclTax * 1.19;
        const totalAmountExclTax = unitPriceExclTax * quantity;
        const totalAmountNet = unitPriceNet * quantity;

        entry.querySelector('input[name$="[unit_price_net]"]').value = unitPriceNet.toFixed(2);
        entry.querySelector('input[name$="[total_amount_excl_tax]"]').value = totalAmountExclTax.toFixed(2);
        entry.querySelector('input[name$="[total_amount_net]"]').value = totalAmountNet.toFixed(2);
    }

    productContainer.addEventListener('input', function (event) {
        if (event.target.matches('input[name$="[unit_price_excl_tax]"], input[name$="[qte]"]')) {
            const entry = event.target.closest('.product-entry');
            calculatePrices(entry);
        }
    });

    productContainer.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('removeBtn')) {
            event.target.closest('.product-entry').remove();
            updateRemoveButtons();
            updateProductIndices(); // Update indices after removal
        }
    });

    addProductBtn.addEventListener('click', function () {
        const newProductEntry = document.querySelector('.product-entry').cloneNode(true);
        newProductEntry.querySelectorAll('input').forEach(input => input.value = '');
        newProductEntry.querySelector('select').selectedIndex = 0;
        newProductEntry.dataset.index = productIndex; // Set the correct index for new entry
        newProductEntry.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${productIndex}]`);
        });
        productContainer.appendChild(newProductEntry);

        updateRemoveButtons();
        productIndex++; // Increment index for the next product entry
    });

    // Initial call to ensure correct button visibility
    updateRemoveButtons();
});
</script>
@endsection