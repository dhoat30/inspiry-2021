import "../css/style.scss"
import Splide from '@splidejs/splide';

// form 
import Form from './modules/Form/Form'
// owl carousel 
import EveryOwlCarousel from './modules/OwlCarousel/EveryOwlCarousel';
// warranty 
import Warranty from './modules/Warranty';
import WallpaperCalc from './modules/WallpaperCalc';
import DesignBoardSaveBtn from './modules/DesignBoardSaveBtn';
import Overlay from './modules/overlay';
import TopNav from './modules/TopNav';
import ShopFav from './modules/ShopFav';
import ToolTip from './modules/ToolTip';

//pop up cart
import PopUpCart from './modules/PopUpCart';


// get product date
import Product from './modules/Product';

// Enquire Modal 
import EnquiryModal from './modules/EnquiryModal/EnquiryModal'

// cart modal 
import CartModal from './modules/CartModal/CartModal'

// auth
import Login from './modules/Auth/Login'

// windcave checkout validation 
import CheckoutInputValidation from './modules/Windcave/CheckoutInputValidation'

// search 
import Search from './modules/Search'

// facet filter
import FacetFilter from './modules/FacetFilter/FacetFilter'

// customer service 
import CustomerServiceMenu from './modules/CustomerService/CustomerServiceMenu'
import ContactForm from './modules/CustomerService/ContactForm'
import FeedbackForm from './modules/CustomerService/FeedbackForm'
let $ = jQuery;

window.onload = function () {
  //send request to 
  //create board function 
  // new Splide('.splide', {
  //   direction: 'ttb',
  //   height: '10rem',
  //   width: 150,
  //   height: 'auto',
  //   perPage: 3,
  //   padding: { top: 0, bottom: 20 }

  // }).mount();
  // $('.flex-control-nav').addClass('splide__list')
  // $('.flex-control-nav li').addClass('splide__slide')


  // enquiry modal 
  const enquiryModal = new EnquiryModal();
  // cart modal 
  const cartModal = new CartModal();
  // form data processing 
  const form = new Form();
  //get product data and show in the quick view
  const product = new Product();
  // every owl carousel
  const everyOwlCarousel = new EveryOwlCarousel();


  const shopFav = new ShopFav();
  const topnav = new TopNav();
  const overlay = new Overlay();
  const designBoardSaveBtn = new DesignBoardSaveBtn();
  const popUpCart = new PopUpCart();



  //Tool tip 
  const toolTip = new ToolTip();

  // login 
  const login = new Login()

  // search 
  const search = new Search()

  // facet filter 
  const facetFilter = new FacetFilter()

  // customer service 
  const customerServiceMenu = new CustomerServiceMenu()
  const contactForm = new ContactForm()
  const feedbackForm = new FeedbackForm()

  //price 
  let pricevalue = document.getElementsByClassName('bc-show-current-price');
  // console.log($('.bc-show-current-price').text);
  //slogan 

  $('.logo-container .slogan').css('opacity', '1');


  //profile navbar


  let profileNavbar = {
    eventListener: function () {
      $('.profile-name-value').click(function (e) {
        let user = document.querySelector('.profile-name-value').innerHTML;
        console.log("click working");
        if (user.includes('LOGIN / REGISTER')) {
          console.log('Log In');
        }
        else {
          e.preventDefault();
          $('.my-account-nav').slideToggle(200, function () {
            $('.arrow-icon').toggleClass('fa-chevron-up');
          });
        }
      })
    }
  }

  profileNavbar.eventListener();
}




//log in 
//const logIn = new LogIn();



const warranty = new Warranty();
const wallpaperCalc = new WallpaperCalc();


// typewriter effect
document.addEventListener('DOMContentLoaded', function (event) {
  // array with texts to type in typewriter
  // get json array from a title on a web page
  let jsonArray = $('.typewriter-query-container div').attr('data-title');

  if (jsonArray) {
    let dataText = JSON.parse(jsonArray);
    // type one text in the typwriter
    // keeps calling itself until the text is finished
    function typeWriter(text, i, fnCallback) {
      // chekc if text isn't finished yet
      if (i < (text.length)) {
        // add next character to h1
        document.querySelector(".typewriter-title").innerHTML = text.substring(0, i + 1) + '<span aria-hidden="true"></span>';

        // wait for a while and call this function again for next character
        setTimeout(function () {
          typeWriter(text, i + 1, fnCallback)
        }, 100);
      }
      // text finished, call callback if there is a callback function
      else if (typeof fnCallback == 'function') {
        // call callback after timeout
        setTimeout(fnCallback, 700);
      }
    }

    // start a typewriter animation for a text in the dataText array
    function StartTextAnimation(i) {
      if (typeof dataText[i] == 'undefined') {
        setTimeout(function () {
          StartTextAnimation(0);
        }, 1000);
      }
      if (dataText) {
        // check if dataText[i] exists
        if (i < dataText[i].length) {
          // text exists! start typewriter animation
          typeWriter(dataText[i], 0, function () {
            // after callback (and whole text has been animated), start next text
            StartTextAnimation(i + 1);
          });
        }
      }

    }
    // start the text animation
    StartTextAnimation(0);
  }

});

// scroll arrow 

let myID = document.getElementById("go-to-header");

var myScrollFunc = function () {
  var y = window.scrollY;
  if (y >= 1200) {
    myID.classList.add("show");
  } else if (y <= 1200) {
    myID.classList.remove("show");
  }
};

window.addEventListener("scroll", myScrollFunc);

// windcave-------------------------------------------------------------------
let onChangeValue
let windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val();

$(document).on('change', '.wc_payment_methods .input-radio', () => {
  onChangeValue = $("input[type='radio'][name='payment_method']:checked").val()
  windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val();
  console.log(onChangeValue)
})

// email validation 


// hide iframe 

const showWindcaveiframe = () => {
  $('.payment-gateway-container').show();
  $('.overlay').show();
}

const hideOverlay = () => {
  $(document).on('click', '#payment-iframe-container .cancel-payment', () => {
    $('.payment-gateway-container').hide();
    $('.overlay').hide();
  })
}
hideOverlay();

// show windcave iframe conditionaly
$(document).on('click', '#place_order', (e) => {
  if (onChangeValue === 'inspiry_payment' || windcavePaymentSelected === 'inspiry_payment') {
    e.preventDefault();

    // validation class 
    const checkoutInputValidation = new CheckoutInputValidation()
    // check if the terms and conditions is checked 
    let termsConditionsCheckbox = $('.validate-required .woocommerce-form__input-checkbox')
    // check if the validation is true
    if (checkoutInputValidation.validate() && termsConditionsCheckbox.is(':checked')) {

      showWindcaveiframe();
    }
    else if (!termsConditionsCheckbox.is(':checked')) {
      $('#payment').append(`<div class="error">*Please check the terms & conditions</div>`)
    }

  }
  else {
    $('#place_order').unbind('click');
  }
})

// validate iframe 
$(document).on('click', '.windcave-submit-button', (e) => {
  e.preventDefault();
  // remove error element 
  $('.error').remove()
  // add loader icon 
  $('.button-container').append('<div class="loader-icon loader--visible"></div>')
  // add overlay 
  $('.white-overlay').show()

  console.log('windcave submit button');

  WindcavePayments.Seamless.validate({
    onProcessed: function (isValid) {
      console.log(isValid)
      console.log('Card is valid')
      if (isValid) {
        WindcavePayments.Seamless.submit({
          showSpinner: true,
          onProcessed: function () {
            // validate transaction by sending a query session reques to the backend
            let valueOfTransaction = validateTransaction();
            valueOfTransaction.then(res => {

              // successful transaction 
              if (res === "true") {
                // remove loader icon 
                $('.loader-icon').remove()
                // hide overlay 
                $('.white-overlay').hide()
                // hide button
                $('.windcave-submit-button').hide()

                // append response text in iframe container 
                $(".woocommerce-checkout").trigger("submit");

                $('#payment-iframe-container .button-container').append(`<p class="success center-align">Successful</p>`)
                WindcavePayments.Seamless.cleanup()

              }

              // failed transaction 
              else {
                // remove loader icon 
                $('.loader-icon').remove()
                // hide overlay 
                $('.white-overlay').hide()
                // append response text in iframe container 
                $('#payment-iframe-container .button-container').append(`<p class="error center-align">${res}</p>`)
                //  add this timeout if it doesn't work 
                // setTimeout(() => {
                //   location.reload();
                // }, 2000)
              }
            })
          },
          onError: function (error) { console.log('submission error') }
        });
      }
    },
    onError: function (error) {
      console.log('this is an error')
      console.log(error)
    }
  });

})



// send data to backend for query session 
async function validateTransaction() {
  let sessionID = $('.windcave-session-id').attr('data-sessionid')
  const body = {
    sessionID: sessionID
  }

  // dynamic url 
  let url = window.location.hostname;
  let filePath;

  if (url === 'testfly3.local') {
    filePath = `https://testfly3.local/wp-json/inspiry/v1/windcave-session-status`
  }

  else {
    filePath = `https://inspiry.co.nz/wp-json/inspiry/v1/windcave-session-status`
  }

  try {
    const response = await fetch(filePath, {
      method: "POST",
      body: JSON.stringify(body),
      headers: {
        'Content-Type': 'application/json',
        "Accept": "application/json",
      }
    })
    const data = await response.json()
    console.log("response in a funtion")
    console.log(data)
    return data
  }
  catch (err) {
    console.log(err)
  }

}

// hide facet if no value 
(function ($) {
  document.addEventListener('facetwp-loaded', function () {
    $.each(FWP.settings.num_choices, function (key, val) {
      var $facet = $('.facetwp-facet-' + key);
      var $parent = $facet.closest('.facet-wrap');
      var $flyout = $facet.closest('.flyout-row');
      if ($parent.length || $flyout.length) {
        var $which = $parent.length ? $parent : $flyout;
        (0 === val) ? $which.hide() : $which.show();
      }
    });
  });
})(jQuery);