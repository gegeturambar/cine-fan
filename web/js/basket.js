let Basket = function(){
    let buttonsAdd,buttonsRemove,url_add,url_remove = "";
    let price = "";
    let action ="";
    let totalprice="";


    this.init=function(urladd,urlremove){
        url_add = urladd;
        url_remove = urlremove;
        buttonsAdd = document.querySelectorAll('button.addTobasket');

        buttonsAdd.forEach(function(button){
            button.addEventListener("click",onClickAdd);
        });

        buttonsRemove = document.querySelectorAll('button.removeFrombasket');
        buttonsRemove.forEach(function(button){
            button.addEventListener("click",onClickRemove);
        });
    }

    function onClickAdd(event){
        event.preventDefault();
        xhr = new XMLHttpRequest();
        let formData = {id: event.currentTarget.dataset.id};

        price = event.currentTarget.dataset.price;
        action = "add";
        xhr.open('POST',url_add,true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send('id=' + formData.id);
        xhr.addEventListener('readystatechange',success);
    }

    function onClickRemove(event){
        event.preventDefault();
        xhr = new XMLHttpRequest();

        let formData = {id: event.currentTarget.dataset.id};
        price = event.currentTarget.dataset.price;

        action = "remove";
        xhr.open('POST',url_remove,true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send('id=' + formData.id);
        xhr.addEventListener('readystatechange',success);
    }

    let success = function(e){
        if(xhr.status === 200 && xhr.readyState === 4){
            xhr.removeEventListener('readystatechange',success);
            let data = JSON.parse(xhr.responseText);
            console.log(data);
            htmlContent = '';
            updateTotalPrice();
            updateTotalPrices();
            updateQuantity();
            updateVisibilityPrice();
        }
    }

    function updateTotalPrice(){
        let totalpriceElem = document.querySelector('#totalprice');
        let initprice = totalpriceElem.textContent ? parseFloat(totalpriceElem.textContent) : 0;
        price = parseFloat(price).toFixed(2);
        if(action == 'add') {
            totalprice = parseFloat(initprice + price ).toFixed(2);
            totalpriceElem.textContent = totalprice;
        }else{
            if( initprice >= price ) {
                totalprice = parseFloat(initprice - price).toFixed(2);
            }else{
                totalprice = 0;
            }
            totalpriceElem.textContent = totalprice;
        }
    }

    function updateVisibilityPrice(){
        let div_basket_price = document.querySelector('#div_basket_price');
        div_basket_price.style.display = totalprice ? 'block' : 'none';
    }

    function updateTotalPrices(){
        let totalprice = document.querySelectorAll('.total_price');
        if(action == 'add') {
            for( i = 0; i < totalprice.length; i++){
                let item = totalprice[i];
                item.textContent = parseFloat(item.textContent) + parseFloat(price);
                if(item.textContent < 0)
                    item.textContent = 0;
            }
        }else{
            for( i = 0; i < totalprice.length; i++){
                let item = totalprice[i];
                item.textContent = parseFloat(item.textContent) - parseFloat(price)
                if(item.textContent < 0)
                    item.textContent = 0;
            }
        }
    }

    function updateQuantity(){
        let quantity = document.querySelectorAll('.quantity');
        if(action == 'add') {
            for( i = 0; i < quantity.length; i++){
                let item = quantity[i];
                item.textContent = parseInt(item.textContent) + 1;
                if(item.textContent < 0)
                    item.textContent = 0;
            }
        }else{
            for( i = 0; i < quantity.length; i++){
                let item = quantity[i];
                item.textContent = parseInt(item.textContent) -1
                if(item.textContent < 0)
                    item.textContent = 0;
            }
        }
    }

}