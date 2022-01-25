let $ = jQuery;

class SingleDesignBoards {
    constructor() {
        this.events();
    }

    events() {
        $('.action-btn-container .share').on('click', () => {
            $('.action-btn-container .share-icons').show();
            $('.overlay').show();
        })

        $('.action-btn-container .share-icons .fa-times').on('click', () => {
            $('.action-btn-container .share-icons').hide();
            $('.overlay').hide();
        })

        //single card share board
        $('.single-board .board-card .share-btn').on('click', this.showCardShareContainer.bind(this));

    }

    showCardShareContainer(e) {
        $(e.target).closest('.pin-options-container').siblings('.share-icon-container').show();
        $('.overlay').show();

        $(e.targer).closest('.pin-options-container').siblings('.share-icon-container').find('.overlay').show();
        $(e.target).closest('.pin-options-container').siblings('.share-icon-container').find('.close-icon').on('click', () => {
            $('.share-icon-container').hide();
            $('.overlay').hide();

        })
    }
}

export default SingleDesignBoards;