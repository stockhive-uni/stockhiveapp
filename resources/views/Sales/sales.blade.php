<x-app-layout>
    <div>
        <p id="total">Total: £0</p>
        <p id="items">Items: 0</p>
    </div>
    <div>
        <input type="text" id="search-box" placeholder="Search items" />
        <label for="search-results">Item</label>
        <select id="search-results"></select>
        <label for="quantity">Quantity</label>
        <input id="quantity" type="number" min ="0" value="0">
        <x-primary-button :nameEnter="'add-button'" :idEnter="'add-button'">Add</x-primary-button>
    </div>
    <form action="{{ route('sales.confirmTransaction') }}" method="POST">
        @csrf
        <div id="basket">
            <!-- Items are placed in this div -->
        </div>
        <x-primary-button>Confirm Transaction</x-primary-button>
    </form>

    @vite(['resources/js/jquery.js'])
    
    <script defer>
        document.addEventListener("DOMContentLoaded", function () {
            const data = {!! json_encode($items) !!}; // Parse php $items into javascript json

            const searchBox = $("#search-box");
            const searchResults = $("#search-results");

            searchBox.on("input", function () {
                const query = $(this).val().toLowerCase();
                const filteredResults = data.filter(item => item.name.toLowerCase().includes(query));

                searchResults.empty();

                if (filteredResults.length > 0) {
                    filteredResults.forEach(result => {
                        searchResults.append(`<option value=${result.id}>${result.name}</option>`);
                    });
                } else {
                    searchResults.append("<option>No results found</option>");
                }
            });

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
                    <input class="quantity-child" type="number" name="quantity[]" value=${quantity.value}>
                    <p>Total: £${itemPrice}</p>
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
                    var newPrice = allPrice - (parseInt(result.price) * parseInt(quantityChild.value));
                    price.innerHTML = `Total: £${newPrice}`;

                    parentNode.remove();
                }
            });
        });
    </script>
</x-app-layout>
