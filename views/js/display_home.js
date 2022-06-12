const BlockScroller = function () {

    const getTranslateValue = function (element) {
        const transform = element.css('transform');
        const matrix = transform.slice(7, transform.length - 1).split(', ');

        if ("none" === transform) {
            return 0
        }

        return Math.abs(parseInt(matrix[5]));
    }

    const move = function (event) {
        const target = $(event.currentTarget);
        const id = target.data('id-block');
        const direction = target.data('direction');
        const gap = 15;
        const content = $("#" + id + " .p-block-content");

        if (content.children().length <= 3) {
            return;
        }

        const elementHigh = $("#" + id + " .p-block-content .p-block-item:first-child").outerHeight();
        const wrapperHeight = $("#" + id + " .p-block-content-wrapper").outerHeight();
        const contentHeight = content.outerHeight();
        const translateContent = getTranslateValue(content);

        if ("down" === direction) {
            if ((contentHeight - translateContent) - elementHigh >= wrapperHeight - gap) {
                const transform = 'translateY(-' + (translateContent + elementHigh + gap) + 'px)';
                content.css('transform', transform);
            }
        }

        if ("up" === direction) {
            const transform = 'translateY(-' + (translateContent - elementHigh - gap) + 'px)';
            content.css('transform', transform);
        }
    }

    const init = function () {
        $("#productBlocks").on('click', '[data-action="p-block-scroll"]', (event) => {
            setTimeout(() => {
                move(event)
            }, 100);
        });
    }
    return {
        init
    }
};

$(document).ready(function () {
    BlockScroller().init();
});