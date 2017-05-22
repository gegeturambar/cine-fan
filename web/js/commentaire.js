let CommentaireList = function(){
    let buttonsAdd,url_add,url_remove = "";
    let action ="";

    this.init=function(urladd){
        url_add = urladd;
        buttonsAdd = document.querySelectorAll('button.addTocommentaire');

        buttonsAdd.forEach(function(button){
            button.addEventListener("click",onClickAdd);
        });
    }

    function onClickAdd(event){
        event.preventDefault();
        // get content comm
        let comm = document.querySelector('#commentaire_to_add').value;

        let formData = {commentaire : comm,movie: event.currentTarget.dataset.id };
        console.log(formData);

        xhr = new XMLHttpRequest();
        //let formData = {id: event.currentTarget.dataset.id};

        action = "add";
        xhr.open('POST',url_add,true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(JSON.stringify(formData));
        xhr.addEventListener('readystatechange',success);
    }

    let success = function(e){
        if(xhr.status === 200 && xhr.readyState === 4){
            xhr.removeEventListener('readystatechange',success);
            let data = JSON.parse(xhr.responseText);
            htmlContent = '';
            updateCommentaireList(data);
        }
    }

    function updateCommentaireList(data){
        let list = document.querySelector('#list_commentaires');
        var nodes = list.querySelectorAll(".commentaire");
        var com = nodes[nodes.length -1 ];
        let newcom = com.cloneNode(true);
        newcom.querySelector(".commentaire_author").textContent = data.user;
        newcom.querySelector(".commentaire_commentaire").textContent = data.commentaire;
        list.appendChild(newcom);
    }

}