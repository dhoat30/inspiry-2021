<?php 
// design board short code
function contact_form_webduel(){ 
    ob_start(); ?> 
    <!-- we will use the below div to add content using React render function -->
    <form id="contact-form" >
        <div class="flex" >
            <div class="label-container">
                <label for="First Name">First Name*</label> 
                <input type="text" name="first-name" id="first-name" required/> 
            </div>
            <div class="label-container">
                <label for="Last Name">Last Name*</label> 
                <input type="text" name="last-name" id="last-name" required/> 
            </div>
        </div>
            <div class="label-container">
                <label for="Email">Email*</label> 
                <input type="email" name="email" id="email" required/> 
            </div>
            <div class="label-container">
                <label for="Phone Number">Phone Number</label> 
                <input type="tel" name="phone-number" id="phone-number" /> 
            </div>
            <div class="label-container">
                <label for="Message">Message</label> 
                <textarea name="message" id="message" ></textarea> 
            </div>
        <button class="primary-button">Send</button>
    </form>
    <?php return ob_get_clean(); 
}

// design board button shortcode
add_shortcode('contact_form_webduel_code', 'contact_form_webduel'); 