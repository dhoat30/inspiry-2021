const $ = jQuery

class Windcave {
    constructor() {
        // place order button event 
        this.onChangeValue = ''
        this.windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val()

        // this.events()
    }

    events() {
        $(document).on('change', '.wc_payment_methods .input-radio', this.radioChangeHandler)

        // show windframe 
        $(document).on('click', '#place_order', this.showWindcaveiFrameHandler)

        // hide windcave iframe
        $(document).on('click', '#payment-iframe-container .cancel-payment', this.hideIframeHandler)
    }

    // radio  change handler
    radioChangeHandler() {
        this.onChangeValue = $("input[type='radio'][name='payment_method']:checked").val()
        this.windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val();
        console.log(this.onChangeValue)
    }

    // show windcave iframe 
    showWindcaveiFrameHandler() {

        console.log("prevented default")
        if (this.onChangeValue === 'inspiry_payment' || this.windcavePaymentSelected === 'inspiry_payment') {
            e.preventDefault();
            console.log("place order button click")
            $('.payment-gateway-container').show();
            $('.overlay').show();
        }

        else {
            $('#place_order').unbind('click');
        }

    }

    // hide windcave iframe 
    hideIframeHandler() {
        $('.payment-gateway-container').hide();
        $('.overlay').hide();
    }


}
export default Windcave