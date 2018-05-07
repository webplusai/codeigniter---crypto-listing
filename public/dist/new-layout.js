

    console.log('scripts inited!');
    var scrollDisabled = false;

    // Main menu events init
    initMainMenu();

    // Video control
    // initVideoControl();

    // Slider
    // initSlider();

    // Init scroll
    // initScroll();

    // Timer
    // initTimer('.timer-container.start-date');
    // initTimer('.timer-container.end-date');

    // Init add avatar btn
    // initAddAvatarBtn();

    // Init filters
    // initProfileFilters();

    // Init user menu dropdown
    // initUserMenuDropdown();

    document.ontouchmove = function(event){
        if(scrollDisabled){
            event.preventDefault();
        }
    };

    function initMainMenu() {
        var body = $('body');
        var mobileMenu = $('.mobile-menu-container');
        var mainMenuContainer = $('.main-menu-container');
        var mainMenu = $('.main-menu');
        var mainMenuItem = mainMenu.find('li');
        var mainMenuLink = mainMenuItem.find('a');
        var mainMenuOpenBtn = $('.btn-mobile-menu');

        if(mainMenu.length === 0) return false;

        mainMenuLink.on('click', function(e){
            // e.preventDefault();

            mainMenuItem.removeClass('active');
            $(this).parent().addClass('active');
        });
        mainMenuOpenBtn.on('click', function(e){
            e.preventDefault();

            if($(this).hasClass('btn-tablet')){
                $(this).toggleClass('open');
                mainMenuContainer.toggleClass('opened');
            } else {
                body.toggleClass('menu-opened');
                scrollDisabled = !scrollDisabled;
                $(this).toggleClass('open');
                mobileMenu.toggleClass('opened');
            }
        });
    }
    function initVideoControl() {
        var videoContainer = $('.video-container');
        var video = document.getElementById('video');
        var videoControl = videoContainer.find('.video-control');

        if(videoContainer.length !== 0){
            videoControl.on('click', function(e){
                e.preventDefault();

                if($(this).hasClass('play')){
                    video.play();
                    $(this).removeClass('play');
                } else {
                    video.pause();
                    $(this).addClass('play');
                }
            });
        }
    }
    function initSlider(){
        var sliderContainer = $(".owl-carousel");
        var sliderControl = $('.slider-control');
        var sliderPagination = $('.slider-pagination');
        var sliderCurrentPage = sliderPagination.find('.current-page');
        var sliderTotalPages = sliderPagination.find('.total-pages');
        var options = {
            items: 1,
            nav: false,
            dots: false,
            loop: false,
            onInitialized: function setPagination(event){
                sliderCurrentPage.text(event.item.index + 1);
                sliderTotalPages.text(event.item.count);
            },
            onChanged: function setPagination(event){
                sliderCurrentPage.text(event.item.index + 1);
                sliderTotalPages.text(event.item.count);
            }
        };

        if(sliderContainer.length === 0) return false;

        var owlSlider = sliderContainer.owlCarousel(options);

        sliderControl.on('click', function(e){
            e.preventDefault();

            if($(this).hasClass('next-slide')){
                owlSlider.trigger('next.owl.carousel');
            } else if ($(this).hasClass('prev-slide')){
                owlSlider.trigger('prev.owl.carousel');
            }
        });


    }
    function initScroll(){
        var headerContainer = $('.header');
        var coverContainer = $('.company-title-section');
        var mainContainer = $('.main, .content-section');

        if(mainContainer.length === 0) return false;

        var mainTopPos = mainContainer.offset().top;
        var iScrollPos = 0;

        $(window).scroll(function () {
            var iCurScrollPos = $(this).scrollTop();

            if (iCurScrollPos > iScrollPos) {
                //Scrolling Down
                fixedCover(false, iCurScrollPos);
            } else {
                //Scrolling Up
                fixedCover(true, iCurScrollPos);
            }
            iScrollPos = iCurScrollPos;
        });

        function fixedCover(issetHeader, iCurScrollPos){
            if(iCurScrollPos >= mainTopPos){

                coverContainer.addClass('fixed');
                headerContainer.addClass('fixed');

                setTimeout(function(){
                    coverContainer.addClass('showed');
                }, 100);

                if(issetHeader) {
                    headerContainer.addClass('showed');
                }else {
                    headerContainer.removeClass('showed');
                }
                mainContainer.css({'margin-top': mainTopPos - 40});
            }else {
                headerContainer.removeClass('fixed showed');
                coverContainer.removeClass('fixed showed');
                mainContainer.css({'margin-top': 0});
            }
            if(issetHeader){
                coverContainer.addClass('with-header');
            }else {
                coverContainer.removeClass('with-header');
            }
        }
    }
    function initTimer(dateTimer){
        var timerContainer = $(dateTimer);
        var timer = timerContainer.find('.timer');
        var dateDesc = timerContainer.find('.date-text');
        var dayLabel = timer.find('.days').find('.counter');
        var hoursLabel = timer.find('.hours').find('.counter');
        var minutesLabel = timer.find('.minutes').find('.counter');
        var secondsLabel = timer.find('.seconds').find('.counter');
        var goalDate = new Date(timerContainer.data('date'));

        // dateDesc.text(timerContainer.data('date') + " UTC");


        setInterval(function(){
            var currDate = new Date();
            var dateInMS = goalDate - currDate;

            var seconds = Math.floor( (dateInMS/1000) % 60 );
            var minutes = Math.floor( (dateInMS/1000/60) % 60 );
            var hours = Math.floor( (dateInMS/(1000*60*60)) % 24 );
            var days = Math.floor( dateInMS/(1000*60*60*24) );

            dayLabel.text(days);
            hoursLabel.text(hours);
            minutesLabel.text(minutes);
            secondsLabel.text(seconds);

        }, 100);
    }
    function initAddAvatarBtn(){
        var btn = $('.btn-avatar-add');

        if(btn.length === 0) return false;

        btn.on('click', function(e){
            e.preventDefault();

            $(this).parent().find('.avatar-control').click();
        });
    }
    function initUserMenuDropdown(){
        var dropdownMenu = $('.user-dropdown-menu');

        if(dropdownMenu.length === 0) return false;

        dropdownMenu.on('click', function(){
            $(this).toggleClass('opened');
        })
    }
    function initProfileFilters(){
        var filtersContainer = $('.filters-group');
        var filterControl = $('#filtersAlertsToggle');

        filterControl.on('change', function(){
            if($(this).prop('checked')){
                filtersContainer.addClass('show');
                setTimeout(function(){
                    filtersContainer.addClass('in');
                }, 100);
            } else {
                filtersContainer.removeClass('in');
                setTimeout(function(){
                    filtersContainer.removeClass('show');
                }, 200);
            }
        });
    }
