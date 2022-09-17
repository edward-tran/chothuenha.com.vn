$(document).ready(function() {

    const buttons = document.querySelectorAll('.category-list li');
    const categories = document.querySelectorAll('.our-services-section__content-container');
    const containerHeights = document.querySelectorAll('.our-services-section__text-container');

    let heightToSet = window.getComputedStyle(containerHeights[2]).height;

    function setContainerHeights() {
        for (let i = 0; i < containerHeights.length; i++) {

            if(i !== 2) {
                containerHeights[i].style.height = heightToSet;
            }
        }
    }

    setContainerHeights();

    for (let i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', () => {
            for (let j = 0; j < buttons.length; j++) {
                if(i !== j) {
                    if(buttons[j].classList.contains('chosen-option')) {
                        categories[j].style.opacity = '0';
                        buttons[j].classList.remove('chosen-option');
                        categories[j].overflow= 'hidden';
                        categories[j].style.height = '0';
                        categories[j].style.visibility = 'hidden';
                    }
                } else {
                    buttons[i].classList.add('chosen-option');
                    categories[i].style.visibility = 'visible';
                    categories[i].style.height = 'fit-content';
                    categories[i].style.opacity = '1';
                }
            }e
        })
    }

})
