$(document).ready(function() {

    let controller = new ScrollMagic.Controller();

    if($(window).width() > 1200){


        new  ScrollMagic.Scene({
            triggerElement: '#project1',
        })
            .setClassToggle('#project1 .right', 'in-view')
            .on("enter", function (event) {
                $('#project1 video').get(0).play();
            })
            .on("leave", function (event) {
                $('#project1 video').get(0).stop();
            })
            .addTo(controller);

        new  ScrollMagic.Scene({
            triggerElement: '#project2',
        })
            .setClassToggle('#project2 .right', 'in-view')
            .on("enter", function (event) {
                $('#project2 video').get(0).play();
            })
            .on("leave", function (event) {
                $('#project2 video').get(0).stop();
            })
            .addTo(controller);

        new  ScrollMagic.Scene({
            triggerElement: '#project3',
        })
            .setClassToggle('#project3 .right', 'in-view')
            .on("enter", function (event) {
                $('#project3 video').get(0).play();
            })
            .on("leave", function (event) {
                $('#project3 video').get(0).stop();
            })
            .addTo(controller);

        new  ScrollMagic.Scene({
            triggerElement: '#project4',
        })
            .setClassToggle('#project4 .right', 'in-view')
            .on("enter", function (event) {
                $('#project4 video').get(0).play();
            })
            .on("leave", function (event) {
                $('#project4 video').get(0).stop();
            })
            .addTo(controller);


    }else{

    }

    jQuery(function($) {
        $(".lazy").lazy();
    });





});
