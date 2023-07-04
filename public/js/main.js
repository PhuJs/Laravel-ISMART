
let inputSearch = document.querySelector("#form_search_input");
let searchResult = document.querySelector('#search_result');
inputSearch.addEventListener('focus', function (e) {
    searchResult.style.display = "block";
    this.addEventListener('input', function (e) {
        let textSearch = e.target.value.trim().toLowerCase();
        let listProducts = document.querySelectorAll('.search_product_item');
        listProducts.forEach(item => {
            if (item.innerText.toLowerCase().includes(textSearch)) {
                // item.classList.remove('hide');
                item.style.display = "flex";
            } else {
                // item.classList.add('hide');
                item.style.display = "none";
            }
        });
    });
});

inputSearch.addEventListener('blur', function (e) {
    setTimeout(function () {
        searchResult.style.display = "none";
    }, 500);
});

function submitForm() {
    document.getElementById("form_filter_product").submit();
}



