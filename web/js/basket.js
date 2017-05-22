let Basket = function(){
    let buttonsAdd,buttonsRemove,url_add,url_remove = "";
    let price = "";
    let action ="";

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
            htmlContent = '';
            updateTotalPrice();
            updateTotalPrices();
            updateQuantity();
        }
    }

    function updateTotalPrice(){
        let totalprice = document.querySelector('#totalprice');
        if(action == 'add') {
            totalprice.textContent = parseFloat(totalprice.textContent) + parseFloat(price);
        }else{
            totalprice.textContent = parseFloat(totalprice.textContent) - parseFloat(price);
        }
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