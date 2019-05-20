require('jquery');
require('./bootstrap');
require('./ziggy');
alertify=require('alertifyjs');

$(document).ready(function () {

    var current_cart_count=0;
    alertify=window.alertify;
    $('.add-item-cart').click(function () {
        addItemToCart($(this).attr('data-equipment-id'));
    });

    $('.remove-item-cart').click(function () {
        removeItemToCart($(this).attr('data-equipment-id'));
    });


    $('.flush-item-cart').click(function () {
        window.alertify.confirm('Are you sure?',function(){
            flushCart();
        });

    });

    axios.get(route('api.cart.index')).then(function (response) {
        if(response.data != undefined && response.data.data != undefined){
                    updateCartCount(response.data.data.length);
                    for(var idx=0;idx<response.data.data.length;idx++){
                        toggleButtons(response.data.data[idx].id);
                    }
        }
    }).catch(error => {
        console.log(error.response);
});

    function toggleButtons(id){
       // $('.add-item-cart[data-equipment-id="'+id+'"]').toggleClass('d-none');
       // $('.remove-item-cart[data-equipment-id="'+id+'"]').toggleClass('d-none');

    }

    function updateCartCount(count) {
        current_cart_count=count;
        $('#shopping_cart_count').html(count);
       /* if(current_cart_count==0){
            $('#shopping_cart_count').addClass('d-none');
            $('.shpping-cart').addClass('d-none');
        }else{
            $('#shopping_cart_count').removeClass('d-none');
            $('.shpping-cart').removeClass('d-none');
        }*/
    }


    function addItemToCart(id) {
        axios.post(route('api.cart.store'),{
            equipment:id
        }).then(function (response) {
            updateCartCount(current_cart_count+1);
            toggleButtons(id);
            alertify.notify(response.data.message,'success');
        }).catch(function (error) {
            if(error.response.status==400){
                alertify.notify(error.response.data.error_message,'error');
            }else{
                console.log(error);
                alertify.notify("Something went wrong, try again.",'error');
            }

        });
    }

    function removeItemToCart(id) {
        axios.delete(route('api.cart.destroy',[id])).then(function (response) {
            updateCartCount(current_cart_count-1);
            toggleButtons(id);
            alertify.notify(response.data.message,'warning');


            //for the shopping cart view
         //   $('.remove-item-cart[data-equipment-id="'+id+'"]').parents('tr').toggleClass('d-none');

        }).catch(function (error) {
            if(error.response.status==400){
                alertify.notify(error.response.data.error_message,'error');
            }else{
                console.log(error);
                alertify.notify("Something went wrong, try again.",'error');
            }

        });
    }

    function flushCart() {
        axios.delete(route('api.cart.flush')).then(function (response) {
            updateCartCount(0);
            alertify.notify(response.data.message,'warning');

            //for the shopping cart view
            //$('.remove-item-cart').parents('tr').toggleClass('d-none');

        }).catch(function (error) {
            if(error.response.status==400){
                alertify.notify(error.response.data.error_message,'error');
            }else{
                console.log(error);
                alertify.notify("Something went wrong, try again.",'error');
            }

        });
    }

});