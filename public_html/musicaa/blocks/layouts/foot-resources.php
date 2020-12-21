<script src="assets/js/jQuery3.4.1.js"></script>
<script src="assets/js/swiper.min.js"> </script>
<script src="assets/js/wow.js"> </script>

<script type="text/javascript">
    $(document).ready(function () {


        new WOW().init();
        new Swiper(' #sections .slider-about  .swiper-container', {
            slidesPerView: 2.6,
            spaceBetween: 30,
            centeredSlides: false,

            pagination: {
                el: ' .slider-about .swiper-pagination',
                clickable: true,
            },



            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },

            breakpoints: {
                320: {
                    slidesPerView: 1.7,
                    spaceBetween: 30,
                    centeredSlides: false,


                },
                375: {
                    slidesPerView: 1.9,
                    spaceBetween: 35,
                    centeredSlides: false,


                },

                460: {
                    slidesPerView: 2.5,
                    spaceBetween: 23,
                    centeredSlides: false,



                },
                768: {
                    slidesPerView: 1.99,
                    spaceBetween: 50,
                    centeredSlides: false,



                },

                800: {
                    slidesPerView:1.9 ,
                    spaceBetween: 80,
                    centeredSlides: false,



                },
                // 1220: {
                //     slidesPerView:2.0,
                //     spaceBetween: 80,
                //     centeredSlides: false,
                // },



            }
        });


        var $header_top = $('header');

        var $fixed = $('.fixed');
        // toggle menu responsive
        var menuStatus = true;
        $('header a.menu  ').on('click', function () {
            //ahmed
            if(menuStatus){
                $('body').addClass('mobile-menu-active');
                $(".mobile-menu").show();
                menuStatus = false;
            }else{
                $('body').removeClass('mobile-menu-active');
                $(".mobile-menu").hide();
                menuStatus = true;
                console.log("no");
            }});
        $(".mobile-menu li a").on("click" , function(e){
            //Hide nav list when we click on ahmad
            menuStatus = true;
            $(".mobile-menu").hide();
            $('body').removeClass('mobile-menu-active');
        });
        $fixed.find('a.toggle-menu-contact').on('click', function() {
            $(this).parent().toggleClass('open-menu ');
            //hide('remove-menu').
        });






        $('body').css('paddingTop',$(' header nav .lm').innerHeight());

        //smoth scroll
        $('header nav .lm li a').click(function (e) {
            // e.preventDefault();
            $('html ,body').animate({
                scrollTop:$( '#' + $(this).data('scroll')).offset().top + 1
            },1000);


        });
        //add active
        $(' header nav .lm li a').click(function () {


            $(this).addClass('active').parent().siblings().find('a').removeClass('active');



        });


        $(window).scroll(function () {

            $('.block').each(function() {
                if ($(window).scrollTop() > $(this).offset().top ){
                    var blockID =$(this).attr('id');
                    $(' header nav .lm a').removeClass('active');

                    $('header nav .lm li a[data-scroll = "' + blockID +  '"]').addClass('active');

                }
            });


            var scrollToTop = $('.scroll-to-top');
            if ($(window).scrollTop() >= 1000) {
                if (scrollToTop.is(':hidden')) {
                    scrollToTop.fadeIn(400);
                }}  else{

                scrollToTop.fadeOut(400);
            }

        });
        $('.scroll-to-top').click(function (e) {
            e.preventDefault();
            $('html ,body').animate({
                scrollTop:0
            },1000);


        });







        // Get the current year for the copyright
        $('#year').text(new Date().getFullYear());



        $("body").on("click" ,"div.swiper-slide" , function(e){
            e.preventDefault();
            let index = $(this).index();
            console.log(index);

            $(".swiper-pagination span.swiper-pagination-bullet:eq("+index+")").click();

        });

        $(document).on("click" , ".swiper-pagination span.swiper-pagination-bullet" , function(){

           let index = $(this).index() ;
            $(".sections-text > .content").hide();
            $(".sections-text > .content-"+index).fadeIn(500);
        });

        $(window).ready(function() {
            // Animate loader off screen
            $(".loader").fadeOut("slow");
        });

    });

</script>
