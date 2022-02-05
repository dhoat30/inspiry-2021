let $ = jQuery;
//Design board save button
class Product {
    constructor() {


        this.events();

    }
    //events
    events() {
        $('.shopping-cart').on('click', this.getProduct);
    }
    //get product datea
    getProduct() {

        //show loader icon
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/product',
            type: 'POST',
            data: {

                'id': 15033
            },
            complete: () => {
            },
            success: (response) => {
                if (response) {
                    console.log(response);

                    //fill heart
                    //  $('.design-board-save-btn-container i').addClass('fas fa-heart');

                }
            },
            error: (response) => {
                console.log('this is an error');
                console.log(response)

            }
        });


    }
    //add project to board
    addToBoard(e) {

        let boardID = $(e.target).attr('data-boardid');
        let boardPostStatus = $(e.target).attr('data-postStatus');

        let postID = $('.choose-board-container').attr('data-post-id');
        let postTitle = $('.choose-board-container').attr('data-post-title');


        //show loader icon
        $(e.target).closest('.board-list-item').find('.custom-loader').addClass('loader--visible');
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/addToBoard',
            type: 'POST',
            data: {
                'board-id': boardID,
                'post-id': postID,
                'post-title': postTitle,
                'status': boardPostStatus
            },
            complete: () => {
                $(e.target).closest('.board-list-item').find('.custom-loader').removeClass('loader--visible');
            },
            success: (response) => {
                console.log('this is a success area')
                if (response) {
                    console.log(response);
                    $('.project-detail-page .design-board-save-btn-container i').attr('data-exists', 'yes');

                    //fill heart
                    //  $('.design-board-save-btn-container i').addClass('fas fa-heart');

                }
            },
            error: (response) => {
                console.log('this is an error');
                console.log(response)
                $(e.target).closest('.board-list-item').find('.custom-loader').removeClass('loader--visible');

            }
        });


    }







}

export default Product;