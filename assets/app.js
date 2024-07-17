import './bootstrap.js';
import './slick.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


$('.slider_testimony').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    infinite: true,
});


$('.dropdown').hover(function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500);
}, function() {
    $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500);
});
$('.dropdown-menu').click(function(e) {
    e.stopPropagation();
});
