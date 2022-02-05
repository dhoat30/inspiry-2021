const $ = jQuery;

class PopUpCart {
    constructor() {

        this.events();
    }

    events() {
        $('.header .shopping-cart .cart-items-header').on('click', this.openCart)
        $(document).on('click', '.cart-box .cont-shopping a', this.closeCart)
        // $('.cart-popup-container .fa-times').on('click', this.closeCart)
        $(document).on('click', '.single_add_to_cart_button', this.ajaxAddToCart)
        // remove item from cart ajax 
        $(document).on('click', '.cart-popup-container .fa-times', this.removeItem)
    }

    //remove item from cart function 
    removeItem(e) {
        e.preventDefault();
        console.log("sending remove request")
        let productID = $(e.target).attr('data-productID');
        let url = 'https://inspiry.co.nz/wp-admin/admin-ajax.php';
        if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
            url = 'http://localhost/inspirynew/wp-admin/admin-ajax.php';
        }

        $.ajax({
            type: "POST",
            url: url,
            data: { action: 'remove_item_from_cart', 'product_id': productID },
            beforeSend: function () {
                $(e.target).removeClass('fa-times fal');
                $(e.target).addClass('loader-icon');
                $(e.target).show();
            },
            success: function (res) {
                console.log(res)
                if (res) {
                    $(e.target).closest('.product-card').hide();
                    location.reload();
                }
            }
        });
    }

    //close cart

    // open cart
    openCart(event) {
        event.preventDefault();
        console.log('slide down cart')
        $('.cart-popup-container').slideToggle('slow');
        $('.header .shopping-cart a i').toggleClass('fa-chevron-up');
    }
    closeCart() {
        $('.cart-popup-container').slideUp('slow')
        $('.header .shopping-cart a i').removeClass('fa-chevron-up');
    }

    ajaxAddToCart(e) {
        e.preventDefault();
        let thisbutton = $(this),
            $form = thisbutton.closest('form.cart'),
            id = thisbutton.val(),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

        $(document.body).trigger('adding_to_cart', [thisbutton, data]);
        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                thisbutton.addClass('added').removeClass('loading');

            },
            success: function (response) {
                $('.cart-popup-container').slideDown();
                // setTimeout(function () { $('.cart-popup-container').slideUp('slow'); }, 3000);

                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, thisbutton]);
                }
            },
        });
    }
}

export default PopUpCart;