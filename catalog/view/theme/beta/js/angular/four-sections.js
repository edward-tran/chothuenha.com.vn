$(document).ready(function () {

    let detailsProduct = document.querySelector('#product .expanding-section__link');
    let detailsWeb = document.querySelector('#web .expanding-section__link');
    let detailsMobile = document.querySelector('#mobile .expanding-section__link');
    let detailsConsulting = document.querySelector('#consulting .expanding-section__link');

    let productService = document.querySelector('#product-service');
    let webService = document.querySelector('#web-service');
    let mobileService = document.querySelector('#mobile-service');
    let consultingService = document.querySelector('#consulting-service');

    function serveProduct() {
        productService.classList.add('service-active');
        document.querySelector('.color-clean-white').style.overflow = 'hidden';
        document.querySelector('#product-service .button').addEventListener('click', () => {
            productService.classList.remove('service-active');
            document.querySelector('.color-clean-white').style.overflowY = 'scroll'
        })
        document.querySelector('#product-service .link-mobile').addEventListener('click', () => {
            productService.classList.remove('service-active')
            serveMobile();
        })
        document.querySelector('#product-service .link-web').addEventListener('click', () => {
            productService.classList.remove('service-active')
            serveWeb();
        })
    }
    function serveWeb() {
        webService.classList.add('service-active');
        document.querySelector('.color-clean-white').style.overflow = 'hidden';
        document.querySelector('#web-service .button').addEventListener('click', () => {
            webService.classList.remove('service-active');
            document.querySelector('.color-clean-white').style.overflowY = 'scroll';
        })
        document.querySelector('#web-service .link-mobile').addEventListener('click', () => {
            webService.classList.remove('service-active')
            serveMobile();
        })
        document.querySelector('#web-service .link-product').addEventListener('click', () => {
            webService.classList.remove('service-active')
            serveProduct();
        })
    }
    function serveConsulting() {
        consultingService.classList.add('service-active');
        document.querySelector('.color-clean-white').style.overflow = 'hidden';
        document.querySelector('#consulting-service .button').addEventListener('click', () => {
            consultingService.classList.remove('service-active');
            document.querySelector('.color-clean-white').style.overflowY = 'scroll';
        })
        document.querySelector('#consulting-service .link-mobile').addEventListener('click', () => {
            consultingService.classList.remove('service-active')
            serveMobile();
        })
        document.querySelector('#consulting-service .link-product').addEventListener('click', () => {
            consultingService.classList.remove('service-active')
            serveProduct();
        });
    }
    function serveMobile() {
        mobileService.classList.add('service-active');
        document.querySelector('.color-clean-white').style.overflow = 'hidden';
        document.querySelector('#mobile-service .button').addEventListener('click', () => {
            mobileService.classList.remove('service-active');
            document.querySelector('.color-clean-white').style.overflowY = 'scroll';
        })
        document.querySelector('#mobile-service .link-web').addEventListener('click', () => {
            mobileService.classList.remove('service-active')
            serveWeb();
        })
        document.querySelector('#mobile-service .link-consulting').addEventListener('click', () => {
            mobileService.classList.remove('service-active')
            serveConsulting();
        })
    }

    detailsProduct.addEventListener('click', () => {
        serveProduct();
    });
    detailsWeb.addEventListener('click', () => {
        serveWeb();
    });
    detailsMobile.addEventListener('click', () => {
        serveMobile();
    });
    detailsConsulting.addEventListener('click', () => {
        serveConsulting();
    });

    $('.close-button').on('click', function(){
        console.log('CLICK CLOSE', $(this));
        $(this).closest('section').removeClass('service-active');
    });

});
