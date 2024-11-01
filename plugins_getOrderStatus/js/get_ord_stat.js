/*
=============================================================================
@(#)O get_ord_stat.js            revision 2.1-5                   01/09/2017
@(#)P jQuery function for ajax send/recv on Order Status Website
@(#)Q bobh                       revision 1.0-1                   08/24/2016
@(#)R /var/www/ordstat/wp-content/plugins/getOrderStatus/js (challenger)
=============================================================================
*/
jQuery(document).ready(function($) {
  // console.log("Document ready");
  $('serverName').on("change", function() {
    $('#get_ord_stat_form').submit();
    var server = $('#serverName').attr('value');
    window.location.replace(server);
    // console.log("Submitted");
    // console.log("server = " + server);
  });
  $('#get_ord_stat').click(function() {
    // console.log("Button clicked");
    var url = window.location.hostname;
    // console.log("orig hostname = " + url);
    switch(url) {
      case "merordstat.furniture-pro.com":
        var server = "defiant.furniture-pro.com";
        break;
      case "orderstatus.buffalo-ea.com":
        var server = "buffalo.buffalo-ea.com";
        break;
      case "orderstatus.tampa-ea.com":
        var server = "tpa.tampa-ea.com";
        break;
      case "orderstatus.dfw-ea.com":
        var server = "dfw.dfw-ea.com";
        break;
      case "dev.orderstatus.dfw-ea.com":
        var server = "dfw.dfw-ea.com";
        break;
    }
    console.log("server = " + server);
    var custno = $('#cust-num-fld-1').val();
    console.log("cust num = " + custno);
    var data = {
      action: 'get_ord_stat',
      server: server,
      custno: custno
    };
    // console.log("send data = " + data);

    $("#ordinfo").append("<strong><br />Loading...</strong>").fadeIn(999);

    $("#ordinfo").load(get_ord_stat_ajax_obj.ajaxurl, {
      action: "get_ord_stat",
      server: server,
      custno: custno
    });
  }).fadeOut('slow').fadeIn('slow');
});

jQuery(document).on("click", "a[name='prtlnk']", function(e) {
  jQuery("#ordinfo").show();
  window.print();
});

function printDiv(divName) {
  var printContents = document.getElementById(divName).innerHTML;
  var originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
}
