let RateMovie = function(){
    let buttonsAdd,buttonsRemove,url_add,url_remove = "";
    let price = "";
    let action ="";
    let movie_id = "";
    let myRating = "";

    this.init=function(key,currentRating,maxRating,movie_id,url){
        // target element
        var el = document.querySelector('#'+key);
        movie_id = movie_id;
// rating instance
        if(url) {
            // callback to run after setting the rating
            var callback = function(rating) {
                //ajax to add rate and return current rate
                rate(rating,movie_id,url);
            };
            myRating = rating(el, currentRating, maxRating, callback);
        }else {
            myRating = rating(el, currentRating, maxRating);
        }
        return myRating;
    }

    function rate(rating, movie_id, url){

        let formData = { rate : rating, movie: movie_id };

        console.log(url);
        xhr = new XMLHttpRequest();

        action = "add";
        xhr.open('POST',url,true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(JSON.stringify(formData));
        xhr.addEventListener('readystatechange',successRate);
    }

    let successRate = function(e){
        if(xhr.status === 200 && xhr.readyState === 4){
            xhr.removeEventListener('readystatechange',successRate);
            let data = JSON.parse(xhr.responseText);
            console.log(data);
            // update rate and detachEvents

            globalRate.setRating(data.global_note);
            //myRating.setRating();
            myRating.detachEvents();
        }
    }

}