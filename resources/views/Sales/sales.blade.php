<x-app-layout>
    <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h1 class="text-2xl text-center">Add items to basket</h1>
        <input type="text" id="search-box" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg w-full my-4" placeholder="Search items" />
        <label for="search-results">Item</label>

        <select id="search-results" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg"></select>
        <label for="quantity">Quantity</label>

        <input id="quantity" class="text-white bg-stockhive-grey hover:shadow-bxs hover:border-accent transition-all hover:ring-accent p-2 rounded-lg" type="number" min ="0" value="0">
        <x-primary-button :nameEnter="'add-button'" :idEnter="'add-button'">Add</x-primary-button>
    </div>
    <form action="{{ route('sales.confirmTransaction') }}" method="POST">
    <div class="lg:w-[85%] m-auto px-4">
            <x-primary-button class="float-right">Confirm Transaction</x-primary-button>
        </div>
        @csrf
        <div id="basket" class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
            <h1 class="text-2xl text-white text-center">Basket</h1>
            <!-- Items are placed in this div -->
        </div>
        <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
        <h1 class="text-2xl text-center">Cart information</h1>
        <p id="total">Total: £0</p>
        <p id="items">Items: 0</p>
    </div>
    </form>
    @if (isset($message))
        @if ($message != "" && $message != null)
            <div class="bg-stockhive-grey-dark text-white shadow-sm rounded-lg mt-8 lg:w-[85%] w-full m-auto p-4">
                <h3>{{ $message }}</h3>
            </div>
        @endif
    @endif

    @vite(['resources/js/jquery.js'])
    
    <script defer>
        // https://www.w3schools.com/jquery/default.asp - Rob
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

                    var allPrice = Math.round(parseFloat(price.innerHTML.replace("Total: £", "") * 100)) / 100;
                    var newPrice = allPrice + itemPrice;
                    price.innerHTML = `Total: £${newPrice}`;

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



                // total with old price removed
                var deletedPrice = basketTotal - itemTotal;

                // New price selected
                var newPrice = parseInt(total) * result.price

                // total with new price included
                var addedQuantity = deletedPrice + newPrice;

                // total with current amount added
                price.innerHTML = `Total: £${addedQuantity}`;

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

                    var id = parentNode.querySelector(".id");
                    var result = data.find(item => item.id === parseInt(id.value));
                    var allPrice = Math.round(parseFloat(price.innerHTML.replace("Total: £", "") * 100)) / 100;
                    var newPrice = allPrice - (parseFloat(result.price) * parseInt(quantityChild.value));
                    price.innerHTML = `Total: £${newPrice}`;

                    parentNode.remove();
                }
            });
        });
    </script>
</x-app-layout>
