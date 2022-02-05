const $ = jQuery

class FacetFilter {
    constructor() {
        this.mobileFilterButton = $('.mobile .filter-title')
        this.fixedFilterButton = $('.fixed-filter-button')
        this.closeButton = $('.mobile-filter-container .close-button')
        this.closeIcon = $('.mobile-filter-container .close-icon')
        this.events()

    }
    events() {
        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            // Do something
            if (scroll > 300) {
                $('.fixed-filter-button').slideDown()
            }
            else {
                $('.fixed-filter-button').slideUp()
            }
        });
        this.mobileFilterButton.on('click', this.showMobileFilterContainer)
        this.fixedFilterButton.on('click', this.showMobileFilterContainer)
        this.closeButton.on('click', this.closeMobileFilterContainer)
        this.closeIcon.on('click', this.closeMobileFilterContainer)
    }

    showMobileFilterContainer() {
        console.log('filter button clicked')
        $('.facet-wp-container').slideDown()
    }
    closeMobileFilterContainer() {
        $('.facet-wp-container').slideUp()
    }
}

export default FacetFilter