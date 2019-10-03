require('../../css/Store/show.scss');

$('#top-bar-homepage').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-homepage').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-products').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-products').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-opinions').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-opinions').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});

$('#top-bar-contact').on('click', function() {
    $('.producer-subpage').addClass('hidden');
    $('#subpage-contact').removeClass('hidden');

    $('.producer-top-bar-item').removeClass('active');
    $(this).addClass('active');
});


$(".producer-top-bar-item").on("click", function(e) {
    window.location.hash = $(e.target).attr("href").substr(1);
});

$(function() {
    var hash = window.location.hash;
    $('.producer-top-bar div[href="' + hash + '"]').click();

    $('.store-opinion-vote-up').on('click', function() {
        var $voteButton = $(this);

        $.ajax({
            type: "GET",
            url: Routing.generate('store_opinion_vote_up', {
                identifier: $voteButton.data('id')
            })
        }).done(function(data) {
            var $voteCount = $voteButton.closest('.store-opinion-vote-up-wrapper').find('span');
            var $oppositeVoteCount = $voteButton.closest('.store-opinion-votes-wrapper').find('.store-opinion-vote-down-wrapper span');
            var $oppositeVoteWrapper = $voteButton.closest('.store-opinion-votes-wrapper').find('.store-opinion-vote-down-wrapper');

            if (data) {
                $voteButton.closest('.store-opinion-vote-up-wrapper').css('color', 'rgb(0, 128, 0)');

                $voteCount.html(Number($voteCount.html()) + 1);

                if ($oppositeVoteWrapper.css('color') == 'rgb(128, 0, 0)') {
                    $oppositeVoteCount.html(Number($oppositeVoteCount.html()) - 1);
                }

                $oppositeVoteWrapper.css('color', '#333');
            } else {
                $voteButton.closest('.store-opinion-vote-up-wrapper').css('color', '#333');

                $voteCount.html(Number($voteCount.html()) - 1);
            }
        }).fail(function() {
            console.log('Error!');
        });

        return false;
    });

    $('.store-opinion-vote-down').on('click', function() {
        var $voteButton = $(this);

        $.ajax({
            type: "GET",
            url: Routing.generate('store_opinion_vote_down', {
                identifier: $voteButton.data('id')
            })
        }).done(function(data) {
            var $voteCount = $voteButton.closest('.store-opinion-vote-down-wrapper').find('span');
            var $oppositeVoteCount = $voteButton.closest('.store-opinion-votes-wrapper').find('.store-opinion-vote-up-wrapper span');
            var $oppositeVoteWrapper = $voteButton.closest('.store-opinion-votes-wrapper').find('.store-opinion-vote-up-wrapper');

            if (data) {
                $voteButton.closest('.store-opinion-vote-down-wrapper').css('color', 'rgb(128, 0, 0)');

                $voteCount.html(Number($voteCount.html()) + 1);

                console.log($oppositeVoteWrapper.css('color'));
                if ($oppositeVoteWrapper.css('color') == 'rgb(0, 128, 0)') {
                    $oppositeVoteCount.html(Number($oppositeVoteCount.html()) - 1);
                }

                $oppositeVoteWrapper.css('color', '#333');

            } else {
                $voteButton.closest('.store-opinion-vote-down-wrapper').css('color', '#333');

                $voteCount.html(Number($voteCount.html()) - 1);
            }
        }).fail(function() {
            console.log('Error!');
        });

        return false;
    });
});
