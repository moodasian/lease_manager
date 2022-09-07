

// function() {
    // $(document).ready(function () {

    // });
// }

// 모달 오픈
$('.ls-detail-title__add-button .ls-primary-button--l').click( function () {
    $('.ls-modal').addClass('open');
    $("body").css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
});
$('.ls-modal .modal-close, .ls-modal-fade').click( function () {
    $('.ls-modal').removeClass('open');
    $("body").css({overflow:'auto'}).unbind('touchmove');
});

// function openModal() { // 검색 열기
//         if()
//         $('.ls-modal').addClass('open');
//         // $('#Fade').fadeIn();
//         // $("#trigger").addClass('open-slide');
//     });
// }
function closeSrch() { // 검색 닫기
    $(document).ready(function () {
        // $('#DqSearchNav').removeClass('open');
        // $('#DqSearchFade').fadeOut();
        // $(".dq-m-header-search-btn").removeClass('open-slide');
        $("body").css({overflow:'auto'}).unbind('touchmove');
    });
}
function openNav() { // 햄버거 네비게이션 열기
    $(document).ready(function () {
        $('#HamburgerNav').addClass('open').css({'overflow':'auto','-webkit-overflow-scrolling':'touch'}).unbind('touchmove');
        $('#HamburgerFade').fadeIn().bind('touchmove', function(e){e.preventDefault()});
        $("body").css({overflow:'hidden'});
    });
}
function closeNav() { // 햄버거 네비게이션 닫기
    $(document).ready(function () {
        $('#HamburgerNav').removeClass('open').css({overflow:'hidden'}).bind('touchmove', function(e){e.preventDefault()});
        $('#HamburgerFade').fadeOut().unbind('touchmove');
        $("body").css({overflow:'auto'});
    });
}