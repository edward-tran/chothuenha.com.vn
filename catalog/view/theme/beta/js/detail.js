$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
    if (o[this.name]) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};
function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}
function validatePhone(phone) {
  var re = /^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/;
  return re.test(String(phone).toLowerCase());
}
function validateName(name) {
  var re = /^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s\W|_]+$/;
  return re.test(String(name).toLowerCase());
}
function validateDescription(description){
  let re =/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s\W|_]+$/;
  return re.test(String(description).toLowerCase());
}

jQuery(function ($)
{
  $.datepicker.regional["vi-VN"] =
	{
		closeText: "Đóng",
		prevText: "Trước",
		nextText: "Sau",
		currentText: "Hôm nay",
		monthNames: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
		monthNamesShort: ["Một", "Hai", "Ba", "Bốn", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười một", "Mười hai"],
		dayNames: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"],
		dayNamesShort: ["CN", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy"],
		dayNamesMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
		weekHeader: "Tuần",
		dateFormat: "dd/mm/yy",
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ""
	};

	$.datepicker.setDefaults($.datepicker.regional["vi-VN"]);
});
// 
// const VIP_TYPES = {
//   4: {
//     name: "Vip 1",
//     type: "vip_1",
//     status: true
//   },
//   3: {
//     name: "Vip 2",
//     type: "vip_2",
//     status: true
//   },
//   2: {
//     name: "Vip 3",
//     type: "vip_3",
//     status: true
//   },
//   1: {
//     name: $.i18n('common-normal'),
//     type: "normal",
//     status: false
//   },
// };

(function () {
  // const loadPredict = () => {
  //   const category = __configData.slug
  //   const articleId = $('[data-id]').data('id');
  //   const article = $('#meey-value').data('article');
  //   const userPrice = parseInt(article.price && article.price.total ? article.price.total : 0);
  //   const isArticleVip = VIP_TYPES[article.subscriptionPriority].status || false
  //   const area = +$('#area').val()
  //   $.ajax({
  //     url: `/api/article/predict/${articleId}?area=${area}&category=${category}`,
  //     type: 'GET',
  //     dataType: 'json',
  //     success: function (result) {
  //       if (result.data && result.data.price > 0) {
  //         const aiPrice = parseInt(result.data.price);
  //         if((isArticleVip && userPrice < aiPrice) || !isArticleVip){
  //           $('#meey-value').show();
  //           $('#meey-value .AI-value').html(result.data.priceFormat);
  //         }
  //       }
  //     },
  //     error: function (error) {

  //     }
  //   });
  // };
  const trackingUser=function(){
    const item_id = $('#nav-tabContent').data('id');
    const category_id = $('#nav-tabContent').data('category-id')
    let page_type='ITEM';
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams && urlParams.get('suggest')=="true"){
       page_type='SUGGESTED_ITEM';
    }
    globalTracking({ item_id, page_type,category_id,  clicked_url: `${window.location.href}` })
  }
  // $(document).ready(function () {
  //   if ($('#contentPreview').length === 0) {
  //     loadPredict();
  //   }
  //   trackingUser()
  // });
}());
(function () {
  $(document).mouseup(function (event) {
    var container = $("#header-search");
    if (!container.is(event.target) && container.has(event.target).length === 0) {
      if ($("#header-search").hasClass("is-sugestion")) {
        $("#header-search").removeClass("is-sugestion");
      }
    }
  });
  // const watchedArticleDetail = () => {
  //     if (!__configData.isLogin) return
  //     const notId = $("#article-watched").data('notid')
  //     $.ajax({
  //         method: "get",
  //         url: "/api/user/get-article-watched",
  //         data: {
  //             notId: notId,
  //             type: 'detail'
  //         },
  //         contentType: "application/json",
  //         success: function (result) {
  //             if (result.error.status) {
  //                 $("#article-watched").css("display", "block");
  //                 const $elm = $("#watched-article-detail")
  //                 $elm.html(result.data);
                  
  //                 $('#article-watched [data-toggle="tooltip"]').tooltip()
  //                   bindEventArticle("#watched-article-detail")
  //                   initLazyLoad()
  //                   $elm.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag');
  //                   $elm.unbind().lightSlider({
  //                       item: 3.6,
  //                       loop: false,
  //                       slideMove: 1,
  //                       easing: "cubic-bezier(0.25, 0, 0.25, 1)",
  //                       auto: false,
  //                       pager: false,
  //                       enableDrag: true,
  //                       slideMargin: 12,
  //                       enableTouch: true,
  //                       pauseOnHover: true,
  //                       onSliderLoad: function (el) {
  //                           $('.home-section-watched .lSAction .lSPrev').addClass('disabled')
  //                       },
  //                       onAfterSlide: function (el) {
  //                           if (el.getCurrentSlideCount() == 1) {
  //                               $('.home-section-watched .lSAction .lSPrev').addClass('disabled')
  //                           } else {
  //                               $('.home-section-watched .lSAction .lSPrev').removeClass('disabled')
  //                           }
  //                           if (el.getCurrentSlideCount()*2 >= $('#watched-article-detail .article-card').length - 2) {
  //                               $('.home-section-watched .lSAction .lSNext').addClass('disabled')
  //                           } else {
  //                               $('.home-section-watched .lSAction .lSNext').removeClass('disabled')
  //                           }
  //                       },
  //                   });
  //                   if(typeof trackingChat !== 'undefined') {
  //                     trackingChat($('#watched-article-detail .tracking-chat'));
  //                   }
  //             }
  //         },
  //         error: function (err) {
  //             $("#article-watched").css("display", "none");
  //             console.log("watchedArticle=>error", JSON.stringify(err));
  //         },
  //     });
  // }
  $(document).ready(function () {
    // watchedArticleDetail()
    // active chọn kiểu book lịch xem nhà
    let selectTypeSchedule=$('#select-type-schedule .tour-booking-mode');
    if(selectTypeSchedule){
      selectTypeSchedule.first().addClass('active');
      selectTypeSchedule.first().find('input').attr('checked',true);
    }

    const showValidate=($this,message)=>{
      $parent = $($this).parents('.form-group')
      $parent.addClass('error')
      $parent.find('.message').text(message)
    }

    const HideValidate=($this)=>{
      $parent = $($this).parents('.form-group')
      $parent.removeClass('error')
      $parent.find('.message').text("")
    }

    $('#tour_booking_phone').change(function () {
      let phone = $(this).val()
      if(phone){
        phone=phone.trim();
      }
      if(!phone){
       return showValidate(this,$.i18n('detail-phone_empty'))
      }
      if (!validatePhone(phone) || phone == '') {
        return showValidate(this,$.i18n('detail-phone_incorrect_format_2'))
      } else {
        HideValidate(this)
      }
    })
    $('#tour_booking_email').change(function () {
      let mail = $(this).val()
      if(mail){
        mail=mail.trim();
      }
      if(!mail){
       return showValidate(this,$.i18n('detail-email_empty'))
      }
      if (!validateEmail(mail) || mail == '') {
        return showValidate(this,$.i18n('detail-email_incorrect_format_2'))
      } else {
        HideValidate(this)
      }
    })
    $('#tour_booking_fullname').change(function () {
      let name = $(this).val()
      if(name){
        name=name.trim();
      }
      if(!name){
       return showValidate(this,$.i18n('detail-name_empty'))
      }
      // if (!validateName(name) || name == '') {
      //   $parent = $(this).parents('.form-group')
      //   $parent.addClass('error')
      //   $parent.find('.message').text($.i18n('detail-fullname_incorrect_format'))
      // } else {
      HideValidate(this)
      // }
    })
    // $('#breadcrumb').owlCarousel({
    //   margin: 0,
    //   nav: false,
    //   loop: false,
    //   autoWidth: true,
    //   dots: false,
    // });
    $(".seemore-right-block .btn-seemor").click(function () {
      var type = $(this).data("key");
      if ($(".right-block." + type).hasClass("expand")) {
        $(".right-block." + type).removeClass("expand");
        $(this).text($.i18n('common-see_more'))
      } else {
        $(".right-block." + type).addClass("expand");
        $(this).text($.i18n('common-collapse'))

      }
    });
    $("#booking").lightSlider({
      item: 4,
      loop: false,
      slideMove: 2,
      easing: "cubic-bezier(0.25, 0, 0.25, 1)",
      auto: false,
      pager: false,
      enableDrag: true,
      slideMargin: 9,
      enableTouch: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            item: 3,
            slideMove: 1,
          },
        },
      ],
      onSliderLoad: function (el) {
        $('.tour-booking .lSAction .lSPrev').addClass('disabled')
        $('#slider_booking_mask').remove();
      },
      onAfterSlide: function (el) {
        if (el.getCurrentSlideCount() == 1) {
          $('.tour-booking .lSAction .lSPrev').addClass('disabled')
        } else {
          $('.tour-booking .lSAction .lSPrev').removeClass('disabled')
        }
        if (el.getCurrentSlideCount()*2 >= $('#booking .card-item').length -2 ) {
          $('.tour-booking .lSAction .lSNext').addClass('disabled')
        } else {
          $('.tour-booking .lSAction .lSNext').removeClass('disabled')
        }
      },
    });

    $("#related-article-detail").lightSlider({
      item: 3.6,
      loop: false,
      slideMove: 1,
      easing: "cubic-bezier(0.25, 0, 0.25, 1)",
      auto: false,
      pager: false,
      enableDrag: true,
      slideMargin: 12,
      enableTouch: true,
      pauseOnHover: true,
      onSliderLoad: function (el) {
        $('.home-section-favorite .lSAction .lSPrev').addClass('disabled')
      },
      onAfterSlide: function (el) {
        if (el.getCurrentSlideCount() == 1) {
          $('.home-section-favorite .lSAction .lSPrev').addClass('disabled')
        } else {
          $('.home-section-favorite .lSAction .lSPrev').removeClass('disabled')
        }
        if (el.getCurrentSlideCount()*2 >= $('#related-article-detail .article-card').length - 2) {
          $('.home-section-favorite .lSAction .lSNext').addClass('disabled')
        } else {
          $('.home-section-favorite .lSAction .lSNext').removeClass('disabled')
        }
      },
    });
  })
})();

/* SLIDE */
(function () {
  let imageSlideRender = false, videoSlideRender = false;
  const initFancybox = () => {
    $slideImage = $('#slide_media_image')
    if ($slideImage && $slideImage.hasClass('is-slide')) {
      $slideImage.find('.owl-item:not(.cloned) [data-fancybox="images"]').fancybox({
        buttons: [
          'slideShow',
          'zoom',
          'fullScreen',
          'close'
        ],
        thumbs: {
          autoStart: true
        },
        afterShow: function (instance) {
          owlSlideHead.trigger('to.owl.carousel', [instance.currIndex])
        }
      });
    } else {
      $('[data-fancybox="images"]').fancybox({
        buttons: [
          'slideShow',
          'zoom',
          'fullScreen',
          'close'
        ],
        thumbs: {
          autoStart: true
        }
      });
    }

  }
  let owlSlideHead = {}
  const initImageSlide = () => {
    const $elm = $('#slide_media_image');
    $elm.show();
    if ($elm.hasClass('is-slide')) {
      if (!imageSlideRender) {
        $('.block-media').removeClass('full-width');
        $elm.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag owl-hidden');
        owlSlideHead = $elm.unbind().owlCarousel({
          margin: 0,
          nav: true,
          loop: false,
          autoWidth: false,
          dots: true,
          items: 1
        });
        imageSlideRender = true;
      }
    } else {
      $('.block-media').addClass('full-width');
    }
  };
  const initVideoSlide = () => {
    const $elm = $('#slide_media_video');
    $elm.show();
    if ($elm.hasClass('is-slide')) {
      if (!videoSlideRender) {
        $('.block-media').removeClass('full-width');
        $elm.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag');
        let owlSlideVideo = $elm.unbind().owlCarousel({
          margin: 0,
          nav: true,
          loop: false,
          dots: true,
          autoWidth: false,
          items: 1
        });
        videoSlideRender = true;
        updateVideoActive(0);
        addSlideVideo(0);

        owlSlideVideo.on('changed.owl.carousel', function (event) {
          updateVideoActive(event.page.index);
          addSlideVideo(event.page.index);
          return true;
        })
      }
    } else {
      $('.block-media').addClass('full-width');
    }
  };
  const init = () => {
    initImageSlide();
    initFancybox()

    const $btnImage = $('.btn-image');
    if ($btnImage.length > 0) {
      $btnImage.click(function () {
        $('.slide-media').hide();
        $('.nav-media .btn').removeClass('active');
        $(this).addClass('active');
        initImageSlide();
        pauseAllVideo();
        initFancybox()
        $('.btn-fancybox').show()
      });
    } else {
      initVideoSlide();
      $('.nav-media .btn.btn-image').addClass('active');
    }
    const $btnVideo = $('.btn-video');
    
      $btnVideo.click(function () {
        
        $('.slide-media').hide();
        $('.nav-media .btn').removeClass('active');
        $(this).addClass('active');
        initVideoSlide();
        $('.btn-fancybox').hide()
      });
   
    const $btnFancyBox = $('.btn-fancybox');
    if ($btnFancyBox.length > 0) {
      $btnFancyBox.click(function () {
        if ($('#slide_media_image.active .owl-item.active .item-media > a').length) {
          $('#slide_media_image.active .owl-item.active .item-media > a').click()
        } else {
          if ($('.not-slide.slide-media.active a').length) {
            $('.not-slide.slide-media.active a').unbind().click()
          }
        }
      });
    }
  };
  init();
}());

/* Tour booking */
(function () {

  const setSelect=(className)=>{
    var x, i, j, l, ll, selElmnt, a, b, c;
    /*look for any elements with the class "className":*/
    $(`.${className} .select-selected`).remove();
    $(`.${className} .select-items`).remove();
    x = document.getElementsByClassName(className);
    
    l = x.length;
    for (i = 0; i < l; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];
      ll = selElmnt.length;
      /*for each element, create a new DIV that will act as the selected item:*/
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      /*for each element, create a new DIV that will contain the option list:*/
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");
      for (j = 0; j < ll; j++) {
        /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            /*when an item is clicked, update the original select box,
            and the selected item:*/
            var y, i, k, s, h, sl, yl;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            sl = s.length;
            h = this.parentNode.previousSibling;
            for (i = 0; i < sl; i++) {
              if (s.options[i].innerHTML == this.innerHTML) {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                y = this.parentNode.getElementsByClassName("same-as-selected");
                yl = y.length;
                for (k = 0; k < yl; k++) {
                  y[k].removeAttribute("class");
                }
                this.setAttribute("class", "same-as-selected");
                break;
              }
            }
            h.click();
        });
        b.appendChild(c);
      }
      x[i].appendChild(b);
      a.addEventListener("click", function(e) {
          /*when the select box is clicked, close any other select boxes,
          and open/close the current select box:*/
          e.stopPropagation();
          closeAllSelect(this);
          this.nextSibling.classList.toggle("select-hide");
          this.classList.toggle("select-arrow-active");
        });
    }
    function closeAllSelect(elmnt) {
      /*a function that will close all select boxes in the document,
      except the current select box:*/
      var x, y, i, xl, yl, arrNo = [];
      x = document.getElementsByClassName("select-items");
      y = document.getElementsByClassName("select-selected");
      xl = x.length;
      yl = y.length;
      for (i = 0; i < yl; i++) {
        if (elmnt == y[i]) {
          arrNo.push(i)
        } else {
          y[i].classList.remove("select-arrow-active");
        }
      }
      for (i = 0; i < xl; i++) {
        if (arrNo.indexOf(i)) {
          x[i].classList.add("select-hide");
        }
      }
    }
    /*if the user clicks anywhere outside the select box,
    then close all select boxes:*/
    document.addEventListener("click", closeAllSelect);
  }

  const convertDateToTimestamp = (date) => {
    const arrayItem=date.split('/');
    const formatArrayItem=arrayItem.reverse().join('/');
    return moment(formatArrayItem).unix();
  }
  $("#scheduleDate" ).change(function() {
    const date=$(this).val();
    if(date){
      setTimeSchedule(convertDateToTimestamp(date))
    }
   
  });

  const setTimeSchedule = (scheduleDate) => {
    const tagSelectTime='select-custom';
    try {
      let getModateNow=moment();
      let getMoScheduleDate=moment(scheduleDate * 1000);
      let getDataScheduleTime=$('#dataScheduleTime').text();
          getDataScheduleTime=JSON.parse(getDataScheduleTime);
      if(getModateNow.format('YYYY-MM-DD')==getMoScheduleDate.format('YYYY-MM-DD')){
        getDataScheduleTime=getDataScheduleTime.filter(item=>item.use>=getModateNow.format('HH:mm'))
      }
      let elementScheduleTime=getDataScheduleTime.map(item=>{
        return `<option value=${item.index}>${item.use}</option>`
      })
      if(elementScheduleTime.length==0){
         elementScheduleTime.unshift(`<option value=''>--:--</option>`)
      }
      
      $('#scheduleTime').html(elementScheduleTime);
      setSelect(tagSelectTime);

      if(getDataScheduleTime.length==0){
         const elementTime= $(`.${tagSelectTime} .select-selected`)
         if(elementTime){
           elementTime.addClass('disable');
         }
      }

      let $ScheduleTime = $('[data-field="scheduleTime"]');
      $ScheduleTime.removeClass('error');
      $ScheduleTime.find('.message').text();
      if(getDataScheduleTime.length==0){
          $ScheduleTime.addClass('error');
          $ScheduleTime.find('.message').text($.i18n('detail-time-not-option'));
      }
    } catch (error) {
        console.log("Error->Set Time: ",error)
    }
  }

  const formatDateSchedule=(date)=>{
    return moment(date).format('DD/MM/YYYY');
  }

  const initDatePicker=()=>{
    
    let dataScheduleDate=$('#dataScheduleDate').text();
    dataScheduleDate=JSON.parse(dataScheduleDate);
    const listDateShow=dataScheduleDate.map(item=>moment(item.time).format('DD/MM/YYYY'));
    $( "#scheduleDate" ).datepicker({ 
      dateFormat: 'dd/mm/yy',
      locale: 'vi',
      beforeShowDay: function(date){
        var str =formatDateSchedule(date)
        return [ listDateShow.includes(str),'',]
    },
    beforeShow: function (input, inst) {
      var rect = input.getBoundingClientRect();
      setTimeout(function () {
        inst.dpDiv.css({ top: rect.top + 45, left: rect.left + 0 });
      }, 0);
  }
    });
  }

  const initFormBooking = () => {
    initDatePicker();
    const mode = $('.tour-booking input[name="tour_booking_mode"]:checked').val();
    $('input[name="tour_booking_mode"][value="' + mode + '"]').prop('checked', true);
    
    const $timeSelected = $('#booking li.active input');
    const scheduleDate = parseInt($timeSelected.val());
    const formatDate=formatDateSchedule(scheduleDate*1000);
    $('#scheduleDate').val(formatDate);
    $('#tour_booking_fullname').val($('#tour_booking_fullname').data('value'))
    $('#tour_booking_phone').val($('#tour_booking_phone').data('value'))
    $('#tour_booking_email').val($('#tour_booking_email').data('value'))
    $('#tour_booking_content').val('')
    $('[data-field]').removeClass('error')
    $('#tour_booking_error_modal .btn-ok').click(function(){
        $('#tour_booking_error_modal').removeClass('show');
    })
    setTimeSchedule(scheduleDate)
  };
  const submitFormBooking = ($form) => {
    $form.find('button.btn-submit').attr('disabled',true)
    const formData = $form.serializeObject();
    const inputData = {
      senderFullname: formData.tour_booking_fullname?formData.tour_booking_fullname.trim():formData.tour_booking_fullname,
      senderPhone: formData.tour_booking_phone?formData.tour_booking_phone.trim():formData.tour_booking_phone,
      senderEmail: formData.tour_booking_email?formData.tour_booking_email.trim():formData.tour_booking_email,
      scheduleDate: convertDateToTimestamp(formData.scheduleDate),
      scheduleTime: formData.scheduleTime,
      content: formData.tour_booking_content,
      type: formData.tour_booking_mode,
      articleId: formData.articleId
    };
    $.ajax({
      url: '/api/article/register-schedule',
      data: JSON.stringify(inputData),
      type: 'POST',
      processData: false,
      contentType: 'application/json',
      success: function (result) {
        $('[data-field]').removeClass('error');
        $('[data-field] .message').html('');
        if (result.error.status === false) {
          if(result.error.validates && result.error.validates.length > 0){
            result.error.validates.map(item => {
              const $formGroup = $('[data-field="' + item.key + '"]');
              $formGroup.addClass('error');
              console.log('item.value=', item.value, $formGroup.find('.message')[0]);
              $formGroup.find('.message').html(item.value);
            });
          }else{
            if(result.error.message){
                $('#tour_booking_error_modal').addClass("show")
                $('#tour_booking_error_modal .message').text(result.error.message);
            }
        }
          
        } else {
          $form[0].reset();
          $('#tour_booking_modal').modal('hide');
          if ($('#tour_booking_finish_modal').length > 0) {
            $('#tour_booking_finish_modal').unbind().remove();
          }
          $('body').append(result.data);
          $('#tour_booking_finish_modal').modal('show');
        }
        $form.find('button.btn-submit').attr('disabled',false)
      },
      error: function (error) {
        $form.find('button.btn-submit').attr('disabled',false)
        console.log(error);
      }
    });
  };
  const init = () => {
    $('.tour-booking-time').click(function () {
      $('.card-item').removeClass('active')
      $(this).addClass('active')
    })
    $('.tour-booking input[name="tour_booking_time"]').change(function () {
      const $parent = $(this).parents('.card-item');
      $('.tour-booking-time').removeClass('active');
      $parent.addClass('active');
      console.log('tour_booking_time=', $('input[name="tour_booking_time"]:checked').val());
    });
    $('.tour-booking input[name="tour_booking_mode"]').change(function () {
      const $parent = $(this).parents('.tour-booking-mode');
      $('.tour-booking-mode').removeClass('active');
      $parent.addClass('active');
      console.log('tour_booking_mode=', $('.tour-booking input[name="tour_booking_mode"]:checked').val());
    });
    $('#btn_show_modal_booking').click(function () {
      $('#tour_booking_modal').modal('show');
      initFormBooking();
    });
    $('#tour_booking_form').submit(function (e) {
      e.preventDefault();
      submitFormBooking($(this));
      return false;
    });
  };
  $(document).ready(function () {
    init();
  });
}());

/* Tab info */
(function () {
  const initActiveTab = (windowOffset) => {
    const nav_profile = $('#nav-profile').offset().top;
    const meey_caculator = $('#meey-caculator').offset().top;
    if (windowOffset < nav_profile) {
      $('#tab_info [data-tab]').removeClass('active');
      $('#tab_info [data-tab="#nav-home"]').addClass('active');
    } else if (windowOffset >= nav_profile && windowOffset < meey_caculator) {
      $('#tab_info [data-tab]').removeClass('active');
      $('#tab_info [data-tab="#nav-profile"]').addClass('active');
    } else if (windowOffset >= meey_caculator) {
      $('#tab_info [data-tab]').removeClass('active');
      $('#tab_info [data-tab="#meey-caculator"]').addClass('active');
    } else {

    }
  };
  const initScrollPage = (windowOffset, tabOffset) => {
    const $tab_info = $('#tab_info');
    if (windowOffset >= tabOffset) {
      if ($tab_info.hasClass('fixed') == false) {
        $tab_info.addClass('fixed');
        $('.mark-tab-info').addClass('active');
      }
    } else {
      if ($tab_info.hasClass('fixed') == true) {
        $tab_info.removeClass('fixed');
        $('.mark-tab-info').removeClass('active');
      }
    }
  };
  // const scrollPage = () => {
  //   const $tab_info = $('#tab_info');
  //   let tabOffset = $tab_info.offset().top;
  //   $(window).scroll(function () {
  //     let windowOffset = $(window).scrollTop();
  //     initScrollPage(windowOffset + 15, tabOffset);
  //     initActiveTab(windowOffset + 100);
  //   });
  //   initScrollPage($(window).scrollTop(), tabOffset);
  //   initActiveTab($(window).scrollTop() + 100);

  //   $('#tab_info [data-tab]').click(function (e) {
  //     e.preventDefault();
  //     const elm = $(this).data('tab');
  //     $('html, body').animate({
  //       scrollTop: $(elm).offset().top - 57
  //     }, 400);
  //     return false;
  //   });
  // };
  const share_fb = (url) => {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, 'facebook-share-dialog', "width=560, height=767")
  }
  const init = () => {
    const $btnReadMore = $('.btn-read-more');
    if ($btnReadMore.length > 0) {
      $btnReadMore.click(function () {
        const $parent = $(this).parents('.description');
        console.log('parent=', $parent);
        if ($parent.hasClass('expand')) {
          $parent.removeClass('expand');
          $(this).html($.i18n('common-expand'));
        } else {
          $parent.addClass('expand');
          $(this).html($.i18n('common-collapse'));
        }
      });
    }
    const $toggleSimpleBlock = $('.toggle-simple-block');
    if ($toggleSimpleBlock.length > 0) {
      $toggleSimpleBlock.click(function () {
        const $parent = $(this).parents('.simple-block');
        if ($parent.hasClass('min')) {
          $parent.removeClass('min');
        } else {
          $parent.addClass('min');
        }
      });
    }
    const $copy_link_article = $('[data-toggle="copy"]');
    if ($copy_link_article.length > 0) {
      $copy_link_article.click(function () {
        const $btn = $(this);
        const $input = $btn.parents('.input-group').find('input');
        if ($input.length > 0) {
          $input.focus();
          $input[0].select();
          document.execCommand('copy');

          $btn.html($.i18n('common-copied'));
          setTimeout(function () {
            $btn.html($.i18n('common-copy_link'));
          }, 2000);
        }
      });
    }

    $('.btn-facebook').click(function () {
      share_fb($(this).data('href'));
    });
  };
  init();
  // scrollPage();
}());

/* Tính lãi suất vay */
(function () {
  function formatNumber(numb, fix = 0) {
    if (!numb) numb = 0
    let fixPow = Math.pow(10, fix);
    numb = Math.round(numb * fixPow) / fixPow;
    return numb.toString().replace(/\d(?=(\d{3})+(?!\d))/g, '$&,');
  }
  const $form = $('#form_calculator');
  const result = {
    prePaid: 0,
    sourcePaid: 0,
    interestPaid: 0,
    perMonth: 0,
    firstMonth: 0
  };
  let schedulePaid = [];


  

  const calculator = () => {
    result.interestPaid = 0
    schedulePaid = [];
    const mode = parseInt($('input[name="mode"]:checked').val());
   // const amount = parseInt($form.find('input[name="amount"]').val().split(',').join(''));
    const amount = 10;
    const time = parseInt($form.find('input[name="time"]').val());
    const interest_rate = parseFloat($form.find('input[name="interest_rate"]').val());
    const price = parseFloat($form.find('input[name="price"]').val());
    if (price) {
      result.prePaid = price > (amount) ? price - amount : 0;
    }
    result.sourcePaid = amount;
    let sourcePerMonth = Math.round(result.sourcePaid / time);

    if (mode == 1) {
      //Số tiền trả theo dư nợ giảm dần
      let remainSource = result.sourcePaid;
      result.firstMonth = sourcePerMonth + (remainSource * interest_rate / (12 * 100));
      for (let i = 0; i < time; i++) {
        const interestPaid = remainSource * interest_rate / (12 * 100);
        result.interestPaid += interestPaid
        schedulePaid.push({
          'remainSource': remainSource,
          'sourcePerMonth': sourcePerMonth,
          'interestPaid': interestPaid
        });
        remainSource = remainSource - sourcePerMonth;
      }
    } else {
      // Số tiền trả đều hàng tháng
      let remainSource = result.sourcePaid;
      result.firstMonth = sourcePerMonth + (remainSource * interest_rate / (12 * 100));
      for (let i = 0; i < time; i++) {
        const interestPaid = result.sourcePaid * interest_rate / (12 * 100);
        result.interestPaid += interestPaid
        schedulePaid.push({
          'remainSource': remainSource,
          'sourcePerMonth': sourcePerMonth,
          'interestPaid': interestPaid
        });
        remainSource = remainSource - sourcePerMonth;
      }
    }
    if ($('#result_pre_paid').length > 0) {
      $('#result_pre_paid').html(formatNumber(result.prePaid));
    }
    if ($('#result_source_paid').length > 0) {
      $('#result_source_paid').html(formatNumber(result.sourcePaid));
    }
    if ($('#result_interest_paid').length > 0) {
      $('#result_interest_paid').html(formatNumber(result.interestPaid));
    }
    if ($('#result_first_month').length > 0) {
      $('#result_first_month').html(formatNumber(result.firstMonth));
    }
  };
  const initPopupSchedule = () => {
    const $modal = $('#calendar_monthly_popup');
    const $list = $modal.find('.list-schedule-paid tbody');
    let html = '';
    schedulePaid.map((item, index) => {
      html += `<tr>
            <td>${index + 1}</td>
            <td>${formatNumber(item.remainSource)}</td>
            <td>${formatNumber(item.sourcePerMonth)}</td>
            <td>${formatNumber(item.interestPaid)}</td>
            <td>${formatNumber(item.sourcePerMonth + item.interestPaid)}</td> 
        </tr>`;
    });
    $list.html(html);
    $('#lsp_total_interest_paid').html(formatNumber(result.interestPaid));
    $('#lsp_total_paid').html(formatNumber(result.sourcePaid + result.interestPaid));
    $modal.modal('show')
  }

  const init = () => {
    $('input[name="amount"]').on('keyup', function () {
      let val = $(this).val().split(',').join('');
      const valFormat = formatNumber(+val)
      $form.find('input[name="amount"]').val(valFormat);
    });
    $('[name="mode"]').change(function () {
      const amount = +$('input[name="amount"]').val().split(',').join('');
      const time = $('input[name="time"]').val()
      const interest_rate = $('input[name="interest_rate"]').val()
      if (!amount || amount < 1 || amount > 15000000000) {
        return
      }
      if (!time || time < 1 || time > 240) {
        return
      }
      if (!interest_rate || interest_rate <= 0 || interest_rate > 100) {
        return
      }
      calculator();
    });
    $('input[name="amount"]').on('change', function () {
      let val = +$(this).val().split(',').join('');
      $parentAmount = $(this).parents('.wrap-progress')
      if (!val || val < 1 || val > 15000000000) {
        $parentAmount.addClass('error')
        return
      } else {
        $parentAmount.removeClass('error')
      }
      $form.find('input[name="amount"]').val(formatNumber(val));
      calculator();
    });
    $('input[name="time"]').on('change', function () {
      let val = +$(this).val();
      $form.find('input[name="time"]').val(val);
      $parentTime = $(this).parents('.wrap-progress')
      if (!val || val < 1 || val > 240) {
        $parentTime.addClass('error')
        return
      } else {
        $parentTime.removeClass('error')
      }
      calculator();
    });
    $('input[name="interest_rate"]').on('change', function () {
      let val = +$(this).val();
      $form.find('input[name="interest_rate"]').val(val);
      $parentInterest = $(this).parents('.wrap-progress')
      if (!val || val <= 0 || val > 100 || val.toString().split('.').join('').length > 4) {
        $parentInterest.addClass('error')
        return
      } else {
        $parentInterest.removeClass('error')
      }
      calculator();
    });
    calculator();

    $('#btn_view_calendar_monthly').click(function (e) {
      e.preventDefault();
      initPopupSchedule();
      return false;
    });
  };
  $(document).ready(function () {

    $("input.number-only").keydown(function (e) {
      const type = $(this).data('type')
      if (e.which == 190 && type == 1) {
        return true
      }
      if ((e.which > 95 && e.which < 106) || e.which == 110) {
        return true;
      }
      if (e.which > 57) {
        return false;
      }
      if (e.which == 32) {
        return false;
      }
      return true;
    });
    init();
  });
}());
(function () {
  $('.show-number').click(function (e) {
    e.preventDefault();
    if ($('[data-phone]').hasClass('toggle')) {
      $('[data-phone]').removeClass('toggle');
      $('[data-phone]').html($('[data-phone]').data('phone').substr(0, 4) + '***');
      $(this).text($.i18n('detail-click_show_number'))
    } else {
      $('[data-phone]').addClass('toggle');
      $('[data-phone]').html($('[data-phone]').data('phone'));
      $(this).text($.i18n('common-collapse'))
    }
    return false;
  });
  $('.card-info-header').click(function () {
    const $parent = $(this).parent()
    if ($parent.hasClass('hide-info')) {
      $parent.removeClass('hide-info')
    } else {
      $parent.addClass('hide-info')
    }
  })
}());


(function () {
  $('#report_phone').change(function () {
    const phone = $(this).val()
    if (!validatePhone(phone) || phone == '') {
      $parent = $(this).parents('.form-group')
      $parent.addClass('error')
      $parent.find('#report_phone').parent().next('.message').text($.i18n('detail-phone_incorrect_format_2'))
    } else {
      $parent = $(this).parents('.form-group')
      $parent.removeClass('error')
      $parent.find('#report_phone').parent().next('.message').text("")
    }
  })
  
  $('#report_fullname').change(function () {
    const name = $(this).val()
    if (!validateName(name) || name == '') {
      $parent = $(this).parents('.form-group')
      $parent.addClass('error')
      $parent.find('#report_fullname').parent().next('.message').text($.i18n('detail-fullname_incorrect_format_2'))
    } else {
      $parent = $(this).parents('.form-group')
      $parent.removeClass('error')
      $parent.find('#report_fullname').parent().next('.message').text($.i18n('detail-fullname_incorrect_format_2'))
    }
  })
  $('#report_email').change(function () {
    const mail = $(this).val()
    if (!validateEmail(mail) || mail == '') {
      $parent = $(this).parents('.form-group')
      $parent.addClass('error')
      $parent.find('.message').text($.i18n('detail-email_incorrect_format_2'))
    } else {
      $parent = $(this).parents('.form-group')
      $parent.removeClass('error')
      $parent.find('.message').text("")
    }
  })


  $('#report_description').change(function () {
    const description = $(this).val()
    if (!validateDescription(description) || description == '') {
      $parent = $(this).parents('.form-group')
      $parent.addClass('error')
      $parent.find('.message').text($.i18n('detail-description_incorrect_format'))
    } else {
      $parent = $(this).parents('.form-group')
      $parent.removeClass('error')
      $parent.find('.message').text("")
    }
  })

  const submitFormReport = ($form) => {
    const fData = $form.serializeObject();
    const fd = {
      description: fData.report_description,
      fullname: fData.report_fullname,
      email: fData.report_email,
      phone: fData.report_phone,
      articleId: fData.articleId,
      reason: fData.reason,
    };
    const reason = $('#report_form').find('input[name="reason"]:checked').data('value');
    $.ajax({
      url: '/api/article/report',
      data: JSON.stringify(fd),
      type: 'POST',
      processData: false,
      contentType: 'application/json',
      success: function (result) {
        $('[data-field]').removeClass('error');
        $('[data-field] .message').html('');
        if (result.data.error && !result.data.error.status && result.data.error.validates.length > 0) {
          result.data.error.validates.map(item => {
            const $formGroup = $(`[data-field="${item.key}"]`);
            $formGroup.addClass('error');
            $formGroup.find('.message').html(item.value);
          });
        } else {
          $form[0].reset();
          $('#modal_report').modal('hide');
          $('#report_finish_modal').modal('show');
        }
      },
      error: function (error) {
        console.log('submitFormReport->error', error);
      }
    });
  };
  $(window).scroll(function () {
      let windowOffset = $(window).scrollTop();
      
      if (windowOffset > 152) {
          $('.filter-header').addClass('top-fixed')
      } else {
          $('.filter-header').removeClass('top-fixed')
      }
      const mainContentHeight = $('.main-content').innerHeight()
      if (windowOffset > 152) {
          if ((windowOffset + 450) > mainContentHeight) {
              $('#right-banner').removeClass('fixed-banner-top').addClass('banner-top-bottom')
              $('#left-banner').removeClass('fixed-banner-top').addClass('banner-top-bottom')
          }else if(windowOffset > 770){
            $('#right-banner').addClass('banner-middle').removeClass('fixed-banner-top').removeClass('banner-top-bottom')
            $('#left-banner').addClass('banner-middle').removeClass('fixed-banner-top').removeClass('banner-top-bottom')
          }else {
              $('#right-banner').addClass('fixed-banner-top').removeClass('banner-top-bottom').removeClass('banner-middle')
              $('#left-banner').addClass('fixed-banner-top').removeClass('banner-top-bottom').removeClass('banner-middle')
          }
      }else {
          $('#right-banner').removeClass('fixed-banner-top').removeClass('banner-top-bottom')
          $('#left-banner').removeClass('fixed-banner-top').removeClass('banner-top-bottom')
      }
  });
  const init = () => {
    $('#report_form').submit(function (e) {
      e.preventDefault();
      submitFormReport($(this));
      return false;
    });
  };
  // $(document).ready(function () {
  //   init();
  //   intervalBanner('left-banner')
  //   intervalBanner('right-banner')
  //   intervalBanner('left-banner-map')
  //   intervalBanner('right-banner-map')
  //   intervalBanner('middle-banner')
  // });
  $(window).resize(function(){
    $("#scheduleDate").datepicker( "hide" );
  });
}())
