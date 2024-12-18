<x-app-layout>
    <div class="p-8 my-4 text-white text-xl bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <h2 class="text-2xl text-white text-center">Search</h2>
        <div class="flex justify-center">
            <input type="text" id="search-box" class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent p-2" placeholder="Search items" />
        </div>
    </div>
    <div class="p-8 my-4 text-white text-xl bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <div class="md:flex md:justify-center md:gap-4">
        <label for="search-results">Item</label>
        <br class="display-block md:hidden">
        <select class="bg-stockhive-grey rounded-lg text-white border-2 hover:shadow-bxs transition-all hover:border-accent p-2" id="search-results"></select>
        <br class="display-block md:hidden">
        <label for="quantity">Quantity</label>
        <br class="display-block md:hidden">
        <input id="quantity" type="number"  class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg" min ="0" value="0">
        </div>
    </div>
    <div class="p-8 my-4 text-white text-xl bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
        <h2 class="text-2xl text-center text-white">Actions:</h2>
        <div class="flex justify-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
        <x-primary-button :nameEnter="'add-button'" :idEnter="'add-button'">Add</x-primary-button>
        </div>
    </div>
    <form action="{{ route('stock-management.toOverview') }}" method="POST">
        <input type="hidden" id="hidden-total" name="total" value=0>
        <input type="hidden" id="hidden-items" name="items" value=0>
        @csrf
        <div id="basket" class="p-8 my-4 text-white text-xl bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
            <h2 class="text-2xl text-center text-white">Basket:</h2>
            <!-- Items are placed in this div -->
        </div>
        <div class="p-8 my-4 text-white text-xl bg-stockhive-grey-dark lg:rounded-lg w-full lg:w-[85%] m-auto">
            <h2 class="text-2xl text-white text-center">Summary:</h2>
            <p id="total">Total: £0</p>
            <p id="items">Items: 0</p>
            <x-primary-button>Confirm Items</x-primary-button>
            @if (isset($error))
            <p class="error">{{ $error }}</p>
            @endif
        </div>
    </form>

    @vite(['resources/js/jquery.js'])

    <script defer>
        // Used knowledge gained from creating sales - Rob
        document.addEventListener("DOMContentLoaded", function () {
            const data = {!! json_encode($items) !!}; // Parse php $items into javascript json

            const searchBox = $("#search-box");
            const searchResults = $("#search-results");

            searchBox.on("input", function () {
                updatedQuery(this);
            });

            function updatedQuery(inputElement) {
                var query = "";
                if (inputElement != null) {
                    query = $(inputElement).val().toLowerCase();
                }
                filteredResults = data.filter(item => item.name.toLowerCase().includes(query));

                searchResults.empty();

                if (filteredResults.length > 0) {
                    filteredResults.forEach(result => {
                        searchResults.append(`<option value=${result.id}>${result.name}</option>`);
                    });
                } else {
                    searchResults.append("<option>No results found</option>");
                }
            }

            updatedQuery(null);

            const basket = document.getElementById("basket");
            const addButton = document.getElementById("add-button");
            const items = document.getElementById("items");
            const price = document.getElementById("total");
            const hiddenItems = document.getElementById("hidden-items")
            const hiddenTotals = document.getElementById("hidden-total")
            const quantity = document.getElementById("quantity");

            addButton.addEventListener("click", function () {
                if (quantity.value > 0) {
                    const result = data.find(item => item.id === parseInt(searchResults.val()));
                    let itemPrice = result.price * quantity.value;
                    basket.innerHTML += `<div>
                    <input class="id" type="hidden" name="id[]" value=${result.id}>
                    <p name="name">${result.name}</p>
                    <input class="quantity-child text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg" type="number" name="quantity[]" value=${quantity.value} min="0">
                    <p class="item-total">Total: £${itemPrice}</p>
                    <x-primary-button :classEnter="'remove-button'">Remove</x-primary-button>
                    </div>`;

                    var allQuantity = parseInt(items.innerHTML.replace("Items: ", ""));
                    var newQuantity = allQuantity + parseInt(quantity.value);
                    items.innerHTML = `Items: ${newQuantity}`;
                    hiddenItems.value = newQuantity;

                    var allPrice = Math.round(parseFloat(price.innerHTML.replace("Total: £", "") * 100)) / 100;
                    var newPrice = allPrice + itemPrice;
                    price.innerHTML = `Total: £${newPrice}`;
                    hiddenTotals.value = newPrice;

                    quantity.value = 0;
                }
            });

            basket.addEventListener("input", function (event) {
                // Gets parent div and relevant items
                const newQuantity = event.target;
                const parentNode = newQuantity.parentNode;
                const id = parentNode.querySelector(".id");

                // Gets selected item from id
                var result = data.find(item => item.id === parseInt(id.value));
                console.log(result);

                // Old items total for item
                const totalNode = parentNode.querySelector(".item-total");
                var itemTotal = totalNode.innerHTML.replace("Total: £", "");

                // Calculates amount that was previously in the box using total price / individual price
                var originalQuantity = itemTotal / parseFloat(result.price);
                console.log(parseFloat(result.price));
                console.log(originalQuantity);

                // Gets basket totals
                var basketTotal = parseFloat(price.innerHTML.replace("Total: £", ""));
                var allQuantity = parseInt(items.innerHTML.replace("Items: ", ""));

                // items with old amount removed
                var deletedQuantity = allQuantity - originalQuantity;

                // Gets new quantity
                var total = 0;
                if (newQuantity.value != "" && newQuantity.value != null) {
                    total = newQuantity.value;
                }

                // items with current amount added
                var addedQuantity = deletedQuantity + parseInt(total);

                // Adds the new amount to items
                items.innerHTML = `Items: ${addedQuantity}`;
                hiddenItems.value = addedQuantity;



                // total with old price removed
                var deletedPrice = basketTotal - itemTotal;

                // New price selected
                var newPrice = parseInt(total) * result.price

                // total with new price included
                var addedQuantity = deletedPrice + newPrice;

                // total with current amount added
                price.innerHTML = `Total: £${addedQuantity}`;
                hiddenTotals.value = addedQuantity;

                // Updates individual item
                totalNode.innerHTML = `Total: £${newPrice}`;
            });

            basket.addEventListener("click", function (event) {
                if (event.target.matches(".remove-button")) {
                    const parentNode = event.target.parentNode;

                    var quantityChild = parentNode.querySelector(".quantity-child");
                    var allQuantity = parseInt(items.innerHTML.replace("Items: ", ""));
                    var newQuantity = allQuantity - parseInt(quantityChild.value);
                    items.innerHTML = `Items: ${newQuantity}`;
                    hiddenItems.value = newQuantity;

                    var id = parentNode.querySelector(".id");
                    var result = data.find(item => item.id === parseInt(id.value));
                    var allPrice = Math.round(parseFloat(price.innerHTML.replace("Total: £", "") * 100)) / 100;
                    var newPrice = allPrice - (parseFloat(result.price) * parseInt(quantityChild.value));
                    price.innerHTML = `Total: £${newPrice}`;
                    hiddenTotals.value = newPrice;

                    parentNode.remove();
                }
            });
        });
    </script>
    <br>
</x-app-layout>