let sendingForm = false;
let currForm;
let currFormId;
let lastEditedInput;



$(document).on('change', '.required-checkbox', function () {
    currForm = $(this).parents('form');
    enableSending(currForm);

});

$(document).on('keyup', '.form-input', function () {
    currForm = $(this).parents('form');
    lastEditedInput = $(this);
    enableSending(currForm);
    if ($(this).hasClass('form-name')) {
        let val = "";
        $(this).val().split(" ").forEach((part, index) => {
            if (index > 0) {
                val += " ";
            }
            val += jsUcfirst(part);
        });
        $(this).val(val);
    }
    if ($(this).attr('id') == 'form-phone') {
        // $(this).val($(this).val().replace(/(?!^)(?=(?:\d{3})+(?:\.|$))/gm, ' '));
        addHyphen($(this));
    }
    if ($(this).hasClass('growing-input')) {
        currForm.find('.ap-msg').val($(this).text());
    }
});

$(document).on('keyup', '.ap-msg', function () {
    currForm = $(this).parents('form');
    lastEditedInput = $(this);

});


$(document).on('focus', '.form-input', function () {
    currForm = $(this).parents('form');
    $(this).removeClass('form-error');
    $(this).removeClass('form-correct');
    console.log('prevAll', $(this).parents('.input-wrapper').prevAll('.input-wrapper .form-input'));
    $(this).parents('.input-wrapper').prevAll('.input-wrapper').each(function () {
        if (!checkFieldState($(this).find('.form-input'))) {
            $(this).find('.form-input').addClass('form-error');
        } else {
            $(this).find('.form-input').addClass('form-correct');
        }
        console.log('heh', $(this).find('.form-input'));
    });
    console.log('nextAll');
    $(this).nextAll('.form-input').each(function () {
        if ($(this).val().length > 0 && !checkFieldState($(this))) {
            $(this).addClass('form-error');
        }
    });
});

$(document).on('focusout', '.form-input', function () {
    console.log('last', lastEditedInput);
    if (!checkFieldState($(this))) {
        $(this).addClass('form-error');
    } else {
        $(this).addClass('form-correct');
    }
});

$(document).on('focus', '.form-input-phone', function () {
    $(this).parents('div').find('#placeholder').addClass('placeholder-focus');
});

$(document).on('focusout', '.form-input', function () {
    $(this).parents('div').find('#placeholder').removeClass('placeholder-focus');
});

$(document).on('click', '.nf__acceptance__show-link', function () {
    currForm = $(this).parents('form');
    let currAcceptance = $(this).parents('.nf__acceptance');
    if (currAcceptance.hasClass('nf__acceptance--show-full')) {
        currAcceptance.removeClass("nf__acceptance--show-full");
        currAcceptance.find('.nf__acceptance__show-link .show-all').show();
        currAcceptance.find('.nf__acceptance__show-link .hide-all').hide();
    } else {
        currAcceptance.addClass("nf__acceptance--show-full");
        currAcceptance.find('.nf__acceptance__show-link .show-all').hide();
        currAcceptance.find('.nf__acceptance__show-link .hide-all').show();
    }
});

$(document).ready(function () {
    $('.nf__acceptance').each(function () {
        let fullHeight = $(this).find('.continuance').height() + 20;
        $(this).css('--full-height', fullHeight + 'px');
        console.log('fullHeight:', fullHeight);
        $(this).find('.continuance').hide();
        $(this).find('.nf__acceptance__show-link').css('width', 'auto');
    })
});


function enableSending(form) {
    if (checkFormStates(form)) {
        $(currForm).find('.form-submit').addClass('disabled');
    } else {
        $(currForm).find('.form-submit').removeClass('disabled');
    }

}

//
function checkFormStates(form) {
    if (sendingForm)
        return true;

    let disabled = false;

    if (currForm) {
        currFormId = currForm.attr('id');
    }

    $(currForm).find('.required-checkbox').each(function () {
        if (!$(this).prop('checked')) {
            disabled = true;
            console.log('disabled 1');
        }
    });

    $(currForm).find('.form-required').each(function () {
        if ($(this).val().length <= 0) {
            disabled = true;
            console.log('disabled 2');
        }
    });

    if ($(currForm).find('.form-mail').val() && !isEmail($(currForm).find('.form-mail').val())) {
        disabled = true;
        console.log('disabled 5', $(currForm).find('.form-mail').val());
    }

    if ($(currForm).find('.form-phone').length > 0 && !isValidNumber($(currForm).find('.form-phone').val())) {
        disabled = true;
        console.log('disabled 3');
    }

    // if ($('#form-phone').val() && !isValidNumber($('#form-phone').val().split("")) && form.hasClass('form--with-phone')) {
    //     disabled = true;
    // }

    return disabled;
}

function checkFieldState(field) {
    if ((field.hasClass('form-required') && field.val().length <= 0) ||
        (field).hasClass('form-mail') && !isEmail(field.val()) ||
        (field.hasClass('form-phone') && !isValidNumber(field.val()))
    ) {
        return false;
    }
    return true;
}

//
enableSending(currForm);


function sendForm(event) {


    event.preventDefault();

    if (!checkFormStates(currForm)) {

        let submitButton = $(`#${currFormId}`).find('.form-submit');
        submitButton.addClass('disabled');
        submitButton.find('.loader').show();
        submitButton.find('.send').hide();
        let acceptanceChecked = false;

        // jesli sa nieobowiazkowe
        // $('.normalized-form .checkbox-label input').each(function () {
        //     if ($(this).prop('checked')) {
        //         acceptanceChecked = true;
        //     }
        // });

        // let data = {
        //     email: $("#form-mail").val(),
        //     name: $("#form-name").val(),
        //     surname: $('#form-surname').val(),
        //     formId: currForm.attr('id').split('--')[1],
        //     listId: '3b630f899b',
        //     acceptanceChecked: acceptanceChecked
        // };
        //
        // if (currForm.hasClass('form--with-phone')) {
        //     data.phone = $("#form-phone").val();
        // }


        // MOCK TEST

        // setTimeout(function () {
        //     // succ
        //
        //     if (currForm.hasClass('new-form__form--dont-hide-form')) {
        //         $(currForm).hide();
        //     }
        //     submitButton.hide();
        //     $(currForm).find('.nf__afc__box').css('display', 'flex');
        //
        //     // err
        //
        //     // sendingForm = false;
        //     // submitButton.find('.loader').hide();
        //     // submitButton.find('.send').show();
        //     // submitButton.removeClass('disabled');
        //     // enableSending(currForm);
        //     // alert('Wystapił błąd podczas przesyłania formularza. Prosimy o kontakt na adres office.pl@connectis.pl');
        //
        //
        // }, 3000);
        //
        // return;

        // dodawanie do autopilota

        // let data = {
        //     contact: {
        //         Email: $(currForm).find('.form-mail').val(),
        //         LeadSource: 'API',
        //         // _autopilot_list: 'contactlist_F73E31D2-F168-4F08-8297-8FAB155678BC' // invo
        //         _autopilot_list: 'contactlist_de0e2cb7-51de-4cae-a497-3dc5056b19ca' // conn
        //     }
        // };

        let data = {
            email: $(currForm).find('.form-mail').val(),
            name: $(currForm).find('.form-name').val(),
            phone: $(currForm).find('.form-input-phone').val(),
            message: $(currForm).find('#form-message').text()
        };


        $.ajax({
            // url: "./assets/php/chimp.php",
            // url:  "./assets/php/subscribe-form.php",
            url: "./assets/php/send-contact-form.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            cache: false,
            success: function (data) {
                console.log(data);
                console.log(currForm);
                fbq('track', 'Contact');
                if (currForm.hasClass('new-form__form--dont-hide-form')) {
                    $(currForm).hide();
                }
                submitButton.hide();
                $(`#${currFormId}`).find('.nf__afc__box').css('display', 'flex');
            },
            error: function (data) {
                sendingForm = false;
                submitButton.find('.loader').hide();
                submitButton.find('.send').show();
                submitButton.removeClass('disabled');
                enableSending(currForm);
                alert('Wystapił błąd podczas przesyłania formularza. Prosimy o kontakt na adres office.pl@connectis.pl');
            },
        });

    } else {
        alert('Wprowadzone w formularzu dane są niepoprawne');
    }

    return;
}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function isValidNumber(number) {
    let numbers = 0;
    let spaces = 0;
    let numbersArray = number.split('');
    // console.log('number:', number);
    numbersArray.forEach(num => {
        if (isNumeric(num)) {
            numbers++;
        }
        // console.log('num:', num);
    });
    // console.log("numbers:", numbers);
    // if (jQuery.browser.mobile) {
    //     return number.length == 9 && numbers == 9;
    // }
    return numbers >= 9;
}

function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function addHyphen(element) {
    if (jQuery.browser.mobile)
        return;

    let val = $(element).val().split(' ').join('');   // Remove dash (-) if mistakenly entered.

    let finalVal = val.match(/.{1,3}/g).join(' ');    // Add (-) after 3rd every char.
    $(element).val(finalVal);		// Update the input box.
}

function isNumeric(str) {
    return /^\d+$/.test(str);
}
