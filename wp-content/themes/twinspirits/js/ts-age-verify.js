/**
 * Verify that the visitor is 21+ before displaying content.
 */

TS = [];
var $ = jQuery.noConflict();

TS.init = function() {
  // if (Cookies.get('TSAgeVerify')) {
  //   if (Cookies.get('TSAgeVerify') == 1) {
  //     $('#age-verify').remove();
  //   } else {
  //     this.setupAgeVerification();
  //   }
  // } else {
    this.setupAgeVerification();
  // }
};

TS.setupAgeVerification = function(){
  $('#age-verify').show();
  $('#age-verify a.yes').on('click', function(e) {
    Cookies.set('TSAgeVerify', 1, { expires: 30 });
    $('#age-verify').remove();
    e.preventDefault();
  });
  $('#age-verify a.no').on('click', function(e) {
    Cookies.set('TSAgeVerify', 0, { expires: 1 });
    window.location.href = "http://www.zooborns.com";
    e.preventDefault();
  });
};

$(document).ready(function() {
  TS.init();
});