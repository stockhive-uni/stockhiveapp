<x-app-layout>
    <div>
        <input type="text" id="search-box" placeholder="Search items" />
        <label for="search-results">Item</label>
        <select id="search-results"></select>
        <label for="quantity">Quantity</label>
        <input id="quantity" type="number" min ="0" value="0">
        <x-primary-button :nameEnter="'add-button'" :idEnter="'add-button'">Add</x-primary-button>
    </div>
    <div id="basket">

    </div>
    @vite(['resources/js/jquery.js'])

    <script defer>
        document.addEventListener("DOMContentLoaded", function () {
            const quantity = document.getElementById("quantity");

            const data = @json($items); // Parse php $items into javascript

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

            addButton.addEventListener("click", function () {
                const result = data.find(item => item.id === parseInt(searchResults.val()));
                basket.innerHTML += `<div>
                <input type="hidden" name="id" value=${result.id}>
                <p name="name">${result.name}</p>
                <input type="number" name="quantity" value=${quantity.value}>
                </div>`;
                quantity.value = 0;

            });
        });
    </script>
</x-app-layout>
