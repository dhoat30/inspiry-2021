let $ = jQuery;
class TradeDirectory{ 
    constructor(){ 
        this.events(); //window.location.hostname
    }
    events(){ 
        // display filter in desktop view
       this.desktopView(); 

        //    slide toggle filters 
        $('.trade-directory .sidebar .title').on('click', this.showFacet); 
        
        // show filter container in desktop view 
        $('.trade-directory .refine-button .button').on('click', this.showFilterContainer);

        // hide sidebar container in mobile
        $('.trade-directory .sidebar .close-icon').on('click', this.hideContainer); 

        // typewriter effect
     

    }

  

    // display filter in desktop view 
    desktopView(){
        var x = window.matchMedia("(min-width: 1100px)");
        if(x.matches){
            $('.facetwp-facet').show();
            $('.facet-wp-code').find('.fal').removeClass('fa-plus');

            $('.facet-wp-code').find('.fal').addClass('fa-minus');
        }
        
    }

    // slide toggle filters 
    showFacet(e){
        $(e.target).closest('.facet-wp-code').find('.facetwp-facet').slideToggle();
        $(e.target).closest('.facet-wp-code').find('.fal').toggleClass('fa-minus');
        $(e.target).closest('.facet-wp-code').find('.fal').toggleClass('fa-plus');

    }

    // show filter container
    showFilterContainer(){
        $('.trade-directory .sidebar').show();
    }

    // hide container
    hideContainer(){
        $('.trade-directory .sidebar').hide();
    }

   
}

export default TradeDirectory; 