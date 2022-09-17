$(document).ready(function () {
    let questionContainer = document.querySelectorAll('.faq-section__question-container');
    let answer = document.querySelectorAll('.faq-section__question-container__answer');

    let height = [];

    for (let i = 0; i < questionContainer.length; i++) {

        let tempHeight = window.getComputedStyle(answer[i]).height;

        height.push(tempHeight);

        answer[i].style.height = '0';

        questionContainer[i].addEventListener('click', () => {

            if(questionContainer[i].classList.contains('active')) {
                questionContainer[i].classList.remove('active');
                answer[i].style.height = '0';
            } else {
                for (let i = 0; i < questionContainer.length; i++) {
                    questionContainer[i].classList.remove('active');
                    answer[i].style.height = '0';
                }

                if(!questionContainer[i].classList.contains('active')){
                    questionContainer[i].classList.add('active');

                    answer[i].style.height = height[i];
                }
            }
        })
    }
})
