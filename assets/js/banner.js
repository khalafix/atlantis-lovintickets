function initBannerSlideshow() {
    var slides = [
        './assets/images/bg/bg-01.webp',
        './assets/images/bg/bg-16.webp',
        './assets/images/bg/bg-02.webp',
        './assets/images/bg/bg-03.webp',
        './assets/images/bg/bg-04.webp',
        './assets/images/bg/bg-05.webp'
    ];

    var currentSlide = 0;
    var isTransitioning = false;
    var $container = $('.slideshow-container');
    var $slideElements = [];

    function createSlides() {
        $.each(slides, function(index, imageSrc) {
            var $slideDiv = $('<div></div>').addClass('slide').css('background-image', 'url(' + imageSrc + ')');
            if (index === 0) {
                $slideDiv.addClass('active');
            } else {
                $slideDiv.addClass('preparing');
            }
            $container.append($slideDiv);
            $slideElements.push($slideDiv);

            $slideDiv.on('transitionend webkitTransitionEnd oTransitionEnd', function(e) {
                onTransitionEnd(e, $slideDiv);
            });
        });
    }

    function onTransitionEnd(event, $currentDiv) {
        if ($currentDiv.hasClass('active') && !isTransitioning) {
            nextSlide();
        }
    }

    function nextSlide() {
        if (isTransitioning) return;
        isTransitioning = true;

        var $curr = $slideElements[currentSlide];
        var nextIndex = (currentSlide + 1) % slides.length;
        var $next = $slideElements[nextIndex];

        $next.removeClass('preparing fade-out').addClass('active').css('z-index', 3);
        $curr.css('z-index', 2);

        setTimeout(function() {
            $curr.css({
                'opacity': '0',
                'transform': 'scale(1.1)'
            });
        }, 1000);

        setTimeout(function() {
            $curr.removeClass('active').addClass('preparing').css({
                'opacity': '',
                'transform': ''
            });
            currentSlide = nextIndex;
            isTransitioning = false;
        }, 1800);
    }

    function startSlideshow() {
        setInterval(function() {
            if (!isTransitioning) {
                nextSlide();
            }
        }, 6000);
    }

    function preloadImages(imageArray) {
        $.each(imageArray, function(i, src) {
            var img = new Image();
            img.src = src;
        });
    }

    createSlides();
    startSlideshow();
    preloadImages(slides);
}

$(document).ready(function() {
    initBannerSlideshow();
});
