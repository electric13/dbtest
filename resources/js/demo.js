function callDelReq(bi_id) {
        let xhr = new XMLHttpRequest();
        let url = "/basket/del";
        let pData = "bi_id="+bi_id;
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(pData);
        xhr.onload = function() {
            if (xhr.status != 200) { 
                console.log(`Ошибка ${xhr.status}: ${xhr.statusText}`); // Например, 404: Not Found
            } else { // если всё прошло гладко, выводим результат
                console.log(`Готово, получили ${xhr.response.length} байт`); // response -- это ответ сервера
                location.reload();
            }
        };
        xhr.onerror = function() {
            console.log(`Ошибка`);
        };
    };

function killBtnListener(event) {
    //console.log(event.target.getAttribute('bi_id'));
    callDelReq(event.target.getAttribute('bi_id'));
}

// заполняем обработчики нажатий, используя параметр bi_id
[...document.querySelectorAll('.killBtn')].forEach(item => {
  item.addEventListener('click', killBtnListener);
});

function ProductItem(id, product) {
    this.id = id;
    this.product = product;
}

function MaterialItem(id, material) {
    this.id = id;
    this.material = material;
}

window.getProductsList = function(){
    return [
	new ProductItem(1, 'Профлист'),
	new ProductItem(2, 'Металлочерепица'),
	new ProductItem(3, 'Гладкий лист')    
    ];
};

window.getMaterialsList = function(){
    return [
	new MaterialItem(1, 'Цинк'),
	new MaterialItem(2, 'RAL8017'),
	new MaterialItem(3, 'RAL9003')    
    ];
};