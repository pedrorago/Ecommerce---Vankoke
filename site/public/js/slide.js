 $(".slide").slick( {
 	dots: true, infinite: true, speed: 300, autoplay: true, autoplaySpeed: 2000, slidesToShow: 1, prevArrow: false, nextArrow: false
 }
 );
 var blendHome = $(".blend-banner");

var slickTrack = $("#BannerHome").find(".slick-track");
slickTrack.prepend(blendHome);